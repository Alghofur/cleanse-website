<?php
class CustomerController extends BaseController {
    
    public function dashboard() {
        Auth::requireRole(ROLE_CUSTOMER);
        
        $user = Auth::user();
        $orders = Order::findByCustomer($user->id);
        $services = Service::all(true);
        
        $data = [
            'user' => $user,
            'orders' => $orders,
            'services' => $services,
            'totalOrders' => count($orders),
            'pendingOrders' => count(array_filter($orders, fn($o) => $o->status === 'pending')),
            'completedOrders' => count(array_filter($orders, fn($o) => $o->status === 'completed'))
        ];
        
        echo $this->view('customer/dashboard', $data);
    }
    
    public function services() {
        Auth::requireRole(ROLE_CUSTOMER);
        
        $services = Service::all(true);
        echo $this->view('customer/services', ['services' => $services]);
    }
    
    public function bookService() {
        Auth::requireRole(ROLE_CUSTOMER);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validation = $this->validate($_POST, [
                'service_id' => 'required|numeric',
                'schedule_date' => 'required|date',
                'schedule_time' => 'required',
                'address' => 'required|min:10',
                'city' => 'required'
            ]);
            
            if (!empty($validation)) {
                $_SESSION['errors'] = $validation;
                Router::back();
            }
            
            $service = Service::find($_POST['service_id']);
            
            if (!$service) {
                $_SESSION['error'] = 'Service not found';
                Router::back();
            }
            
            $orderData = [
                'customer_id' => Auth::user()->id,
                'service_id' => $_POST['service_id'],
                'schedule_date' => $_POST['schedule_date'],
                'schedule_time' => $_POST['schedule_time'],
                'address' => $_POST['address'],
                'city' => $_POST['city'],
                'postal_code' => $_POST['postal_code'] ?? '',
                'special_instructions' => $_POST['instructions'] ?? '',
                'total_price' => $service->calculatePrice()
            ];
            
            $order = Order::create($orderData);
            
            if ($order) {
                $_SESSION['success'] = 'Service booked successfully!';
                Router::redirect(APP_URL . '/customer/payment/' . $order->id);
            }
        }
        
        $services = Service::all(true);
        echo $this->view('customer/book', ['services' => $services]);
    }
    
    public function payment($orderId) {
        Auth::requireRole(ROLE_CUSTOMER);
        
        $order = Order::find($orderId);
        
        if (!$order || $order->customer_id !== Auth::user()->id) {
            $_SESSION['error'] = 'Order not found';
            Router::redirect(APP_URL . '/customer/dashboard');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $paymentData = [
                'order_id' => $orderId,
                'amount' => $order->total_price,
                'payment_method' => $_POST['payment_method'],
                'transaction_id' => $_POST['transaction_id'] ?? uniqid('TRX-')
            ];
            
            $payment = Payment::create($paymentData);
            
            if ($payment) {
                $_SESSION['success'] = 'Payment processed successfully!';
                Router::redirect(APP_URL . '/customer/orders');
            }
        }
        
        echo $this->view('customer/payment', ['order' => $order]);
    }
    
    public function rateService($orderId) {
        Auth::requireRole(ROLE_CUSTOMER);
        
        $order = Order::find($orderId);
        
        if (!$order || $order->customer_id !== Auth::user()->id || $order->status !== 'completed') {
            $_SESSION['error'] = 'Invalid order for rating';
            Router::redirect(APP_URL . '/customer/dashboard');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ratingData = [
                'order_id' => $orderId,
                'customer_id' => Auth::user()->id,
                'rating' => $_POST['rating'],
                'review' => $_POST['review'] ?? '',
                'staff_rating' => $_POST['staff_rating'] ?? null,
                'staff_review' => $_POST['staff_review'] ?? null
            ];
            
            $rating = Rating::create($ratingData);
            
            if ($rating) {
                $_SESSION['success'] = 'Thank you for your feedback!';
                Router::redirect(APP_URL . '/customer/orders');
            }
        }
        
        echo $this->view('customer/rate', ['order' => $order]);
    }
    
    public function orders() {
        Auth::requireRole(ROLE_CUSTOMER);
        
        $orders = Order::findByCustomer(Auth::user()->id);
        echo $this->view('customer/orders', ['orders' => $orders]);
    }
    
    public function profile() {
        Auth::requireRole(ROLE_CUSTOMER);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userData = [
                'full_name' => $_POST['full_name'],
                'phone' => $_POST['phone'],
                'email' => $_POST['email']
            ];
            
            if (!empty($_POST['password'])) {
                $userData['password'] = $_POST['password'];
            }
            
            $user = Auth::user()->update($userData);
            
            if ($user) {
                $_SESSION['success'] = 'Profile updated successfully!';
            }
        }
        
        echo $this->view('customer/profile', ['user' => Auth::user()]);
    }
}
?>
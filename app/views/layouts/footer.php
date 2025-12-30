    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-bucket-fill me-2"></i>Cleanse
                    </h5>
                    <p class="text-muted">Professional cleaning services for homes and offices. We make spaces shine!</p>
                    <div class="social-links">
                        <a href="#" class="text-white me-3"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-white me-3"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-white me-3"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-white"><i class="bi bi-whatsapp"></i></a>
                    </div>
                </div>
                <div class="col-md-2">
                    <h6 class="fw-bold">Services</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none">Home Cleaning</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Office Cleaning</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Deep Cleaning</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Carpet Cleaning</a></li>
                    </ul>
                </div>
                <div class="col-md-2">
                    <h6 class="fw-bold">Company</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none">About Us</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Careers</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Contact</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6 class="fw-bold">Contact Info</h6>
                    <ul class="list-unstyled text-muted">
                        <li class="mb-2">
                            <i class="bi bi-geo-alt me-2"></i>
                            123 Clean Street, City 12345
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-telephone me-2"></i>
                            +1 234 567 8900
                        </li>
                        <li>
                            <i class="bi bi-envelope me-2"></i>
                            hello@cleanse.com
                        </li>
                    </ul>
                </div>
            </div>
            <hr class="text-muted my-4">
            <div class="text-center text-muted">
                &copy; <?= date('Y') ?> Cleanse. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="<?= APP_URL ?>/assets/js/script.js"></script>
    
    <script>
        // Theme Toggle
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = themeToggle.querySelector('i');
        
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.setAttribute('data-bs-theme', 'dark');
            themeIcon.classList.replace('bi-moon-stars', 'bi-sun');
        }
        
        themeToggle.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            document.documentElement.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            if (newTheme === 'dark') {
                themeIcon.classList.replace('bi-moon-stars', 'bi-sun');
            } else {
                themeIcon.classList.replace('bi-sun', 'bi-moon-stars');
            }
        });
    </script>
</body>
</html>
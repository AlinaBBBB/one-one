<footer id="footer" class="bg-dark text-white pt-5 pb-4 mt-auto">
    <div class="container">
        <div class="row">
            <!-- О бренде -->
            <div class="col-lg-4 col-md-6 mb-4">
                <a href="{{ route('home') }}" class="navbar-brand text-white mb-3 d-block">
                    <strong>ONEONE</strong>
                </a>
                <p class="text-light opacity-75 mb-4" style="font-size: 0.9rem;">
                    Минималистичная женская одежда премиум-класса. Черно-белая эстетика, 
                    качественные материалы, безупречный крой. Одежда для тех, кто ценит 
                    простоту и элегантность.
                </p>
                <div class="social-links d-flex gap-3">
                    <a href="#" class="text-white opacity-75 hover-opacity-100 transition" style="font-size: 1.2rem;">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="#" class="text-white opacity-75 hover-opacity-100 transition" style="font-size: 1.2rem;">
                        <i class="bi bi-telegram"></i>
                    </a>
                    <a href="#" class="text-white opacity-75 hover-opacity-100 transition" style="font-size: 1.2rem;">
                        <i class="bi bi-youtube"></i>
                    </a>
                    <a href="#" class="text-white opacity-75 hover-opacity-100 transition" style="font-size: 1.2rem;">
                        <i class="bi bi-pinterest"></i>
                    </a>
                </div>
            </div>

            <!-- Информация -->
            <div class="col-lg-2 col-md-6 mb-4">
                <h6 class="fw-bold mb-3" style="letter-spacing: 0.5px;">ИНФОРМАЦИЯ</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="/about" class="text-white text-decoration-none opacity-75 hover-opacity-100 transition">О бренде</a></li>
                    <li class="mb-2"><a href="/delivery" class="text-white text-decoration-none opacity-75 hover-opacity-100 transition">Доставка</a></li>
                    <li class="mb-2"><a href="/returns" class="text-white text-decoration-none opacity-75 hover-opacity-100 transition">Возврат</a></li>
                    <li class="mb-2"><a href="/size-guide" class="text-white text-decoration-none opacity-75 hover-opacity-100 transition">Таблица размеров</a></li>
                    <li class="mb-2"><a href="/faq" class="text-white text-decoration-none opacity-75 hover-opacity-100 transition">FAQ</a></li>
                </ul>
            </div>

            <!-- Помощь -->
            <div class="col-lg-2 col-md-6 mb-4">
                <h6 class="fw-bold mb-3" style="letter-spacing: 0.5px;">ПОМОЩЬ</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="/contact" class="text-white text-decoration-none opacity-75 hover-opacity-100 transition">Контакты</a></li>
                    <li class="mb-2"><a href="/support" class="text-white text-decoration-none opacity-75 hover-opacity-100 transition">Поддержка</a></li>
                    <li class="mb-2"><a href="/wholesale" class="text-white text-decoration-none opacity-75 hover-opacity-100 transition">Оптовым клиентам</a></li>
                    <li class="mb-2"><a href="/collaboration" class="text-white text-decoration-none opacity-75 hover-opacity-100 transition">Сотрудничество</a></li>
                    <li class="mb-2"><a href="/blog" class="text-white text-decoration-none opacity-75 hover-opacity-100 transition">Блог</a></li>
                </ul>
            </div>

            <!-- Контакты -->
            <div class="col-lg-4 col-md-6 mb-4">
                <h6 class="fw-bold mb-3" style="letter-spacing: 0.5px;">КОНТАКТЫ</h6>
                <ul class="list-unstyled text-light opacity-75">
                    <li class="mb-2 d-flex align-items-start">
                        <i class="bi bi-envelope me-2 mt-1"></i>
                        <span>shop@oneone-fashion.com</span>
                    </li>
                    <li class="mb-2 d-flex align-items-start">
                        <i class="bi bi-telephone me-2 mt-1"></i>
                        <span>+7 (495) 123-45-67</span>
                    </li>
                    <li class="mb-2 d-flex align-items-start">
                        <i class="bi bi-clock me-2 mt-1"></i>
                        <span>Ежедневно 9:00 - 21:00</span>
                    </li>
                    <li class="mb-2 d-flex align-items-start">
                        <i class="bi bi-geo-alt me-2 mt-1"></i>
                        <span>г. Москва, ул. Минимальная, д. 1</span>
                    </li>
                </ul>
                
                <!-- Рассылка -->
                <div class="mt-4">
                    <p class="small mb-2 opacity-75">Подпишитесь на новости и получите скидку 10%</p>
                    <form class="d-flex">
                        <input type="email" class="form-control rounded-0 border-0 bg-light text-dark" placeholder="Ваш email" style="font-size: 0.9rem;">
                        <button type="submit" class="btn btn-light rounded-0 border-0 ms-2">
                            <i class="bi bi-arrow-right"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <hr class="my-4 opacity-25">

        <!-- Нижняя часть футера -->
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <p class="mb-0 small opacity-75">© 2024 ONEONE. Все права защищены.</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <div class="d-flex justify-content-center justify-content-md-end gap-4">
                    <a href="/privacy" class="text-white text-decoration-none small opacity-75 hover-opacity-100 transition">Политика конфиденциальности</a>
                    <a href="/terms" class="text-white text-decoration-none small opacity-75 hover-opacity-100 transition">Пользовательское соглашение</a>
                </div>
            </div>
        </div>
        
        <!-- Платежные системы -->
        <div class="row mt-4">
            <div class="col-12 text-center">
                <div class="d-flex justify-content-center gap-3 opacity-75">
                    <i class="bi bi-credit-card" style="font-size: 1.5rem;"></i>
                    <i class="bi bi-paypal" style="font-size: 1.5rem;"></i>
                    <i class="bi bi-coin" style="font-size: 1.5rem;"></i>
                    <i class="bi bi-shield-check" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
    .hover-opacity-100:hover {
        opacity: 1 !important;
    }
    
    .transition {
        transition: all 0.3s ease;
    }
    
    footer a:hover {
        text-decoration: underline !important;
    }
</style>
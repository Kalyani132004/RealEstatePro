<footer class="pt-5 pb-4 mt-5" style="background: var(--rep-primary); color: rgba(255,255,255,0.85);" id="contact">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <h4 class="rep-h4 text-white mb-3">RealEstate<span style="color:var(--rep-accent)">Pro</span></h4>
                <p class="rep-small" style="color: rgba(255,255,255,0.7)">
                    A modern property listing, virtual tour &amp; enquiry portal connecting buyers, renters, and verified agents across the country.
                </p>
                <div class="d-flex gap-2 mt-3">
                    <a href="#" class="rep-theme-toggle" style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.15);"><i class="bi bi-facebook text-white"></i></a>
                    <a href="#" class="rep-theme-toggle" style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.15);"><i class="bi bi-instagram text-white"></i></a>
                    <a href="#" class="rep-theme-toggle" style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.15);"><i class="bi bi-twitter-x text-white"></i></a>
                    <a href="#" class="rep-theme-toggle" style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.15);"><i class="bi bi-linkedin text-white"></i></a>
                </div>
            </div>

            <div class="col-lg-2 col-md-6">
                <h6 class="text-white fw-600 mb-3">Quick Links</h6>
                <ul class="list-unstyled d-flex flex-column gap-2">
                    <li><a href="{{ route('home') }}" class="rep-small text-decoration-none" style="color: rgba(255,255,255,0.7)">Home</a></li>
                    <li><a href="{{ route('properties.search') }}" class="rep-small text-decoration-none" style="color: rgba(255,255,255,0.7)">Properties</a></li>
                    <li><a href="{{ route('register') }}" class="rep-small text-decoration-none" style="color: rgba(255,255,255,0.7)">Become an Agent</a></li>
                    <li><a href="#" class="rep-small text-decoration-none" style="color: rgba(255,255,255,0.7)">About Us</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6">
                <h6 class="text-white fw-600 mb-3">Popular Categories</h6>
                <ul class="list-unstyled d-flex flex-column gap-2">
                    <li><a href="{{ route('properties.search') }}?category=apartments" class="rep-small text-decoration-none" style="color: rgba(255,255,255,0.7)">Apartments</a></li>
                    <li><a href="{{ route('properties.search') }}?category=villas" class="rep-small text-decoration-none" style="color: rgba(255,255,255,0.7)">Villas</a></li>
                    <li><a href="{{ route('properties.search') }}?category=plots" class="rep-small text-decoration-none" style="color: rgba(255,255,255,0.7)">Plots &amp; Land</a></li>
                    <li><a href="{{ route('properties.search') }}?category=commercial" class="rep-small text-decoration-none" style="color: rgba(255,255,255,0.7)">Commercial</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6">
                <h6 class="text-white fw-600 mb-3">Newsletter</h6>
                <p class="rep-small mb-3" style="color: rgba(255,255,255,0.7)">Get the latest listings straight to your inbox.</p>
                <form class="d-flex gap-2" id="repNewsletterForm">
                    <input type="email" class="form-control rep-input" placeholder="Your email" required>
                    <button type="submit" class="rep-btn rep-btn-accent rep-btn-sm"><i class="bi bi-send"></i></button>
                </form>
            </div>
        </div>

        <hr style="border-color: rgba(255,255,255,0.15)" class="my-4">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <p class="rep-small mb-0" style="color: rgba(255,255,255,0.6)">&copy; {{ date('Y') }} RealEstatePro. All rights reserved.</p>
            <p class="rep-small mb-0" style="color: rgba(255,255,255,0.6)">Privacy Policy &middot; Terms of Service</p>
        </div>
    </div>
</footer>

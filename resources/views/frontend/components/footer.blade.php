<!-- Landing Page Footer -->
<footer class="footer-section bg-dark text-white py-5">
    <div class="container">
        <div class="row">
            <!-- Brand and Description -->
            <div class="col-lg-4 mb-4">
                <img src="{{ asset($data['logo']) }}" alt="Logo" height="30" class="mb-3">
                <p class="text-white-75 mb-3">{{ $data['description'] }}</p>

                <!-- Social Links -->
                <div class="d-flex gap-3">
                    @foreach($data['social_links'] as $platform => $url)
                        <a href="{{ $url }}" class="text-white-75 hover-light" target="_blank">
                            <i class="align-middle" data-feather="{{ $platform }}"></i>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Menu Columns -->
            @foreach($data['menu_columns'] as $column)
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3">{{ $column['title'] }}</h6>
                    <ul class="list-unstyled">
                        @foreach($column['links'] as $link)
                            <li class="mb-2">
                                <a href="{{ $link['url'] }}" class="text-white-75 text-decoration-none hover-light">
                                    {{ $link['title'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach

            <!-- Contact Info -->
            <div class="col-lg-4 mb-4">
                <h6 class="fw-bold mb-3">Contact Us</h6>
                @if(!empty($data['contact_info']))
                    <ul class="list-unstyled">
                        @if(!empty($data['contact_info']['address']))
                            <li class="mb-2 d-flex">
                                <i class="align-middle me-2" data-feather="map-pin"></i>
                                <span class="text-white-75">{{ $data['contact_info']['address'] }}</span>
                            </li>
                        @endif
                        @if(!empty($data['contact_info']['email']))
                            <li class="mb-2 d-flex">
                                <i class="align-middle me-2" data-feather="mail"></i>
                                <a href="mailto:{{ $data['contact_info']['email'] }}" class="text-white-50 text-decoration-none hover-light">
                                    {{ $data['contact_info']['email'] }}
                                </a>
                            </li>
                        @endif
                        @if(!empty($data['contact_info']['phone']))
                            <li class="mb-2 d-flex">
                                <i class="align-middle me-2" data-feather="phone"></i>
                                <a href="tel:{{ $data['contact_info']['phone'] }}" class="text-muted text-decoration-none hover-light">
                                    {{ $data['contact_info']['phone'] }}
                                </a>
                            </li>
                        @endif
                    </ul>
                @endif
            </div>
        </div>

        <!-- Copyright -->
        <div class="border-top border-gray-800 mt-4 pt-4">
            <div class="row align-items-center">
                <div class="col-md">
                    <p class="text-muted mb-0">© {{ date('Y') }} {{ $site->name ?? config('app.name') }}. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
.hover-light {
    transition: color 0.2s ease-in-out;
}
.hover-light:hover {
    color: #fff !important;
}
</style>

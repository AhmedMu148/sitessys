@php
    $title = $data['title'] ?? 'Our Team';
    $subtitle = $data['subtitle'] ?? 'Meet the experts behind our success';
    $members = $data['members'] ?? [
        [
            'name' => 'John Doe',
            'position' => 'CEO & Founder',
            'image' => 'https://source.unsplash.com/random/400x400/?portrait,man',
            'bio' => 'Over 15 years of experience in technology and business leadership.',
            'social' => [
                'linkedin' => '#',
                'twitter' => '#'
            ]
        ],
        [
            'name' => 'Jane Smith',
            'position' => 'CTO',
            'image' => 'https://source.unsplash.com/random/400x400/?portrait,woman',
            'bio' => 'Expert in cloud architecture and digital transformation.',
            'social' => [
                'linkedin' => '#',
                'twitter' => '#'
            ]
        ],
        [
            'name' => 'Mike Johnson',
            'position' => 'Lead Developer',
            'image' => 'https://source.unsplash.com/random/400x400/?portrait,developer',
            'bio' => 'Passionate about creating innovative solutions using modern technologies.',
            'social' => [
                'linkedin' => '#',
                'github' => '#'
            ]
        ]
    ];
@endphp

<section class="team-section py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">{{ $title }}</h2>
            <p class="lead text-muted">{{ $subtitle }}</p>
        </div>
        
        <div class="row g-4">
            @foreach($members as $member)
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="position-relative">
                            <img src="{{ $member['image'] }}" class="card-img-top" alt="{{ $member['name'] }}">
                            @if(isset($member['social']) && is_array($member['social']))
                                <div class="member-social position-absolute bottom-0 start-0 w-100 p-3 bg-gradient-dark">
                                    <div class="d-flex justify-content-center gap-3">
                                        @foreach($member['social'] as $platform => $url)
                                            <a href="{{ $url }}" class="text-white" target="_blank">
                                                <i data-feather="{{ $platform }}" style="width: 18px; height: 18px;"></i>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title mb-1">{{ $member['name'] ?? 'Team Member' }}</h5>
                            <p class="text-primary mb-3">{{ $member['position'] ?? 'Position' }}</p>
                            <p class="card-text text-muted">{{ $member['bio'] ?? 'Team member biography.' }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

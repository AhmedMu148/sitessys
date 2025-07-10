@php
    $title = $data['title'] ?? 'Latest Blog Posts';
    $subtitle = $data['subtitle'] ?? 'Stay updated with our latest articles and insights';
    $posts = $data['posts'] ?? [];
    $action = $data['action'] ?? ['text' => 'View All Posts', 'url' => '/blog', 'btn_style' => 'primary', 'btn_size' => 'lg'];
@endphp

<section class="blog-section py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">{{ $title }}</h2>
            <p class="lead text-muted">{{ $subtitle }}</p>
        </div>
        
        <div class="row g-4">
            @foreach($posts as $post)
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <img src="{{ $post['image'] }}" class="card-img-top" alt="{{ $post['title'] }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-{{ $post['category_bg'] ?? 'primary' }}">{{ $post['category'] ?? 'General' }}</span>
                                <small class="text-muted">{{ $post['date'] ?? date('M d, Y') }}</small>
                            </div>
                            <h5 class="card-title mb-3">{{ $post['title'] ?? 'Blog Post Title' }}</h5>
                            <p class="card-text text-muted">{{ $post['excerpt'] ?? 'Blog post excerpt.' }}</p>
                        </div>
                        <div class="card-footer bg-white border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i data-feather="user" class="text-muted me-1" style="width: 14px; height: 14px;"></i>
                                    <small class="text-muted">{{ $post['author'] ?? 'Author' }}</small>
                                </div>
                                <div class="d-flex gap-3">
                                    <small class="text-muted">
                                        <i data-feather="eye" class="me-1" style="width: 14px; height: 14px;"></i>
                                        {{ $post['views'] ?? '0' }}
                                    </small>
                                    <small class="text-muted">
                                        <i data-feather="message-square" class="me-1" style="width: 14px; height: 14px;"></i>
                                        {{ $post['comments'] ?? '0' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        @if(isset($action['text']))
            <div class="text-center mt-5">
                <a href="{{ $action['url'] }}" class="btn btn-{{ $action['btn_style'] ?? 'primary' }} btn-{{ $action['btn_size'] ?? 'lg' }}">
                    {{ $action['text'] }}
                </a>
            </div>
        @endif
    </div>
</section>

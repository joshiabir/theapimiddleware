<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @php
        $base_url = config('app.url'); // Uses your APP_URL from .env
        $pages = [
            '/',
            '/register',
            '/login',
            '/blog',
        ];

        foreach($blogs as $blog)
        {
            array_push($pages,'/blog/'.$blog->slug);
        }
    @endphp

    @foreach ($pages as $page)
        <url>
            <loc>{{ $base_url . $page }}</loc>
            <lastmod>{{ now()->format('Y-m-d') }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach
</urlset>

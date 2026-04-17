@php
    $siteName = (string) setting('site_name', 'Universitas Nahdlatul Ulama Kalimantan Timur');
    $siteDescription = (string) setting('meta_description', 'Website resmi Universitas Nahdlatul Ulama Kalimantan Timur.');
    $siteLogo = trim((string) setting('site_logo', ''));
    $siteLogoUrl = $siteLogo !== ''
        ? (str($siteLogo)->startsWith(['http://', 'https://']) ? $siteLogo : asset('storage/' . $siteLogo))
        : null;
    $latitude = trim((string) setting('site_latitude', ''));
    $longitude = trim((string) setting('site_longitude', ''));
    $sameAs = array_values(array_filter([
        setting('social_facebook', 'https://www.facebook.com/unukaltim.official'),
        setting('social_instagram', 'https://www.instagram.com/unukaltim/'),
        setting('social_youtube', 'https://www.youtube.com/@unukaltim2402'),
        setting('social_tiktok', 'https://www.tiktok.com/@unukaltim.official'),
        setting('google_maps_url', ''),
    ]));

    $structuredData = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'CollegeOrUniversity',
        '@id' => url('/#organization'),
        'name' => $siteName,
        'alternateName' => 'UNU Kaltim',
        'url' => url('/'),
        'logo' => $siteLogoUrl,
        'image' => $siteLogoUrl,
        'description' => $siteDescription,
        'telephone' => setting('site_phone', ''),
        'email' => setting('site_email', ''),
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => setting('site_address', ''),
            'addressLocality' => setting('site_city', 'Samarinda'),
            'addressRegion' => setting('site_region', 'Kalimantan Timur'),
            'postalCode' => setting('site_postal_code', ''),
            'addressCountry' => setting('site_country', 'ID'),
        ],
        'geo' => is_numeric($latitude) && is_numeric($longitude) ? [
            '@type' => 'GeoCoordinates',
            'latitude' => (float) $latitude,
            'longitude' => (float) $longitude,
        ] : null,
        'sameAs' => $sameAs,
    ]);
@endphp

<script type="application/ld+json">
    @json($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
</script>

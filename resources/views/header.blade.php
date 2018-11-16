<!doctype html>
<html lang="ko" prefix="op: http://media.facebook.com/op#">
<head>
    <meta charset="utf-8">
    <meta property="op:markup_version" content="v1.0">
    <!-- URL of the web version of this article -->
    <!-- TODO: Change the domain to match the domain of your website -->
    <link rel="canonical" href="http://www.yourdomain.com/news/articleView.html?idxno={{ $article->externUid }}">
    <!-- The style to be used for this article -->
    {{--<meta property="fb:article_style" content="myarticlestyle">--}}
</head>
<body>
    <article>
        <header>
            <!-- The title and subtitle shown in your Instant Article -->
            <h1>{{ $title }}</h1>
            {{--<h2>Article Subtitle</h2>--}}

            <!-- A kicker for your article -->
            <h3 class="op-kicker">
                This is a kicker
            </h3>

            <!-- The date and time when your article was originally published -->
            {{--<time class="op-published" datetime="2014-11-11T04:44:16Z">November 11th, 4:44 PM</time>--}}
            <time class="op-published" datetime="{{ $dateModified }}">{{ $dateModified }}</time>

            <!-- The date and time when your article was last updated -->
            <time class="op-modified" dateTime="{{ $dateModified }}">{{ $dateModified }}</time>

            <!-- The authors of your article -->
            <address>
                <a rel="facebook" href="https://www.facebook.com/pageid">YourCorp</a>
            </address>
            <address>
                <a>{{ $author }}</a>
            </address>

            <!-- The cover image shown inside your article -->
            <!-- TODO: Change the URL to a live image from your website -->
            <figure>
                <img src="{{ $image['url'] }}" />
                <figcaption>This image is amazing</figcaption>
            </figure>
        </header>

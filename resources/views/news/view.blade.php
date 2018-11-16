@extends('../index')

@section('content')
    <!-- Article body goes here -->

    <!-- Body text for your article -->
    <p> Article content </p>

    <!-- A video within your article -->
    <!-- TODO: Change the URL to a live video from your website -->
    <figure>
        <video>
            <source src="http://mydomain.com/path/to/video.mp4" type="video/mp4" />
        </video>
    </figure>

    <!-- An ad within your article -->
    <!-- TODO: Change the URL to a live ad from your website -->
    <figure class="op-ad">
        <iframe src="https://www.adserver.com/ss;adtype=banner320x50" height="60" width="320"></iframe>
    </figure>
@endsection
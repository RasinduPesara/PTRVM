<?php
require "init.php";
$link = $_GET['link'];

parse_str($link, $urlData);
$my_id = array_values($urlData)[0];

$videoFetchURL = "http://www.youtube.com/get_video_info?&video_id=" . $my_id . "&asv=3&el=detailpage&hl=en_US";
$videoData = get($videoFetchURL);

parse_str($videoData, $video_info);

$video_info = json_decode(json_encode($video_info));
if (!$video_info->status ===  "ok") {
    die("error in fetching youtube video data");
}
$videoTitle = $video_info->title;
$videoAuthor = $video_info->author;
$videoDurationSecs = $video_info->length_seconds;
$videoDuration = secToDuration($videoDurationSecs);
$videoViews = $video_info->view_count;

//change hqdefault.jpg to default.jpg for downgrading the thumbnail quality
$videoThumbURL = "http://i1.ytimg.com/vi/{$my_id}/hqdefault.jpg";

if (!isset($video_info->url_encoded_fmt_stream_map)) {
    die('No data found');
}

$streamFormats = explode(",", $video_info->url_encoded_fmt_stream_map);

if (isset($video_info->adaptive_fmts)) {
    $streamSFormats = explode(",", $video_info->adaptive_fmts);
    $pStreams = parseStream($streamSFormats);
}
    $cStreams = parseStream($streamFormats);


?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>PTRVM - DOWNLOAD</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>
	<body class="is-loading">

		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Header -->
					<header id="header">
						<a href="index.html" class="logo">PTRVM YOUTUBE DOWNLOADER</a>
					</header>

				<!-- Nav -->
					<nav id="nav">
						<ul class="links">
							<li class="active"><a href="index.html">Put Link</a></li>
							<li><a href="getting.html">Download</a></li>
							<li><a href="help.html">Help</a></li>
							<li><a href="about.html">About</a></li>
							<li><a href="contact.html">Contact</a></li>
						</ul>
						<ul class="icons">
							<li><a href="#" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
							<li><a href="#" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
							<li><a href="#" class="icon fa-instagram"><span class="label">Instagram</span></a></li>
							<li><a href="#" class="icon fa-github"><span class="label">GitHub</span></a></li>
						</ul>
					</nav>

				<!-- Main -->
					<div id="main">

						<!-- Post -->
							<section class="post">
								<header class="major">
									<div class="col-sm-4">
										
									<h3><?php echo $videoTitle ;?></h3>
									</div>
								</header>
								<div style="float: right;">
								<h3>VIDEO INFO</h3>
								Duration :<?php echo $videoDuration; ?><br>
								Author :<?php echo $videoAuthor; ?><br>
								Views :<?php echo $videoViews; ?>
								</div>
								<div class="image main" style="width: 50%;"><img src="<?php echo $videoThumbURL ;?>" alt="" /></div>

								<div id="lpanel" class="col-sm-12" style="height: auto; width: 100%;">
                <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>Format</th>
                            <th>Quality</th>
                            <th>Size</th>
                            <th>Download</th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cStreams as $stream): ?>
                                <?php $stream = json_decode(json_encode($stream)) ;?>
                                    <tr>
                                        <td><?php echo $stream->type ?></td>
                                        <td><?php echo $stream->quality ?></td>
                                        <td><?php echo $stream->size ?></td>
                                        <td><a href="<?php echo $stream->url; ?>" download ><button class="btn btn-sm btn-primary">Download</button></a></td>
                                     </tr>
                                 <?php endforeach ?> 
                        </tbody>
                      </table> 
                  </div>
								
				</section>
			<!-- Footer -->
			<footer id="footer">
				<center><h4>A project by FOUNDATION OF GOODNESS</h4></center>
			</footer>

					</div>
			</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.scrollex.min.js"></script>
			<script src="assets/js/jquery.scrolly.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>

	</body>
</html>
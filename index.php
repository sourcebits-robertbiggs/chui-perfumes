<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<title>Perfumes App</title>
	<?php

	$agent = $_SERVER['HTTP_USER_AGENT'];

	if (preg_match("/android/i", $agent) || preg_match("/chrome/i", $agent))
	 {
		echo '<link rel="stylesheet" href="chui/chui.android-3.0.4.min.css">';
	 }
	 elseif (preg_match("/Trident/i", $agent))
	 {
		echo '<link rel="stylesheet" href="chui/chui.win-3.0.4.min.css">';
	 }
	 elseif (preg_match("/Safari/i", $agent))
	 {
		echo '<link rel="stylesheet" href="chui/chui.ios-3.0.4.min.css">';
	 }
	 ?>
	<script type="text/javascript" src="chui/chocolatechip-3.0.4.min.js"></script>
	<script src="chui/chui-3.0.4.min.js"></script>
	<script>
		$(function() {
			$.perfumeData = {};
			$.ajax({
				url : "data/perfumes.json", 
				dataType: 'json',
				success: function(data) {
					$.perfumeData = data;
				}
			});

			// Data and output for first list of genres:
			var genres = [{title: 'Ladies', genre: 'ladies'},{title: 'Men', genre: 'men'},{title: 'Kids', genre: 'kids'}];
			genres.forEach(function(ctx) {
				$('#perfumeGenres').append("<li class='nav' data-genre='" + ctx.genre +"' data-goto='perfumeList'><h3>" + ctx.title +"</h3></li>")
			});

			// Template for perfumes lists
			// Attach item SKU to list.
			// This will be used to grab the item from the data array
			// when the user selects and item.
			var itemTempl = "<li class='comp' class='show-detail' data-sku='[[= data.sku ]]' data-goto='detail'>\
				  <aside>\
						<img src='[[= data.img_prev ]]'>\
					</aside>\
					<div>\
						<h3 class='productTitle'>[[= data.product_title ]]</h3>\
						<h4>[[= data.short_description ]]</h4>\
					</div>\
					<aside>\
						<span class='counter'>$[[= data.wholesale_price ]]</span>\
						<span class='show-detail'></span>\
					</aside>\
				</li>"
			// Template for detail of selected perfume:
			var detailTempl = '<li>\
			    <img src="[[= data.img_prev ]]">\
				<h3 class="productTitle">[[= data.product_title ]]</h3>\
				<h4><span class="sku">SKU: [[= data.sku ]]</span><span class="counter">$[[= data.wholesale_price ]]</span></h4>\
				<p class="longDescription">[[= data.long_description ]]</p>\
			</li>';

			// Initialize templates:
			var itemTmpl8 = $.template(itemTempl);
			var detailTmpl8 = $.template(detailTempl);


			$('#perfumeGenres').on('singletap', 'li', function() {
				var genre = $(this).attr('data-genre');
				var list = $('#available_perfumes');
				if (genre === 'men') {
					list.empty();
					$.perfumeData.men.forEach(function(ctx) {
						list.append(itemTmpl8(ctx));
					});
					list.attr('data-genre', 'men')
					// Update forward interface to user's choice:
					$('#perfumesGenreTitle').html('Men');
					$('#backToGenre').html('Men');
				} else if (genre === 'ladies') {
					list.empty();
					$.perfumeData.ladies.forEach(function(ctx) {
						list.append(itemTmpl8(ctx));
					});
					list.attr('data-genre', 'ladies');
					// Update forward interface to user's choice:
					$('#perfumesGenreTitle').html('Ladies');
					$('#backToGenre').html('Ladies');

				} else {
					list.empty();
					$.perfumeData.kids.forEach(function(ctx) {
						list.append(itemTmpl8(ctx));
					});
					list.attr('data-genre', 'kids');
					// Update forward interface to user's choice:
					$('#perfumesGenreTitle').html('Kids');
					$('#backToGenre').html('Kids');
				}
			}); 

			$('#available_perfumes').on('singletap', 'li', function() {
				var genre = $(this).parent().attr('data-genre');
				var sku = $(this).attr('data-sku');
				// Find item in array based on SKU from chosen item:
				var chosenPerfume = $.perfumeData[genre].filter(function( obj ) {
  					return obj.sku === sku;
				})[0];
				// Update forward interface to user's choice:
				$('#perfumeDetail').html(detailTmpl8(chosenPerfume));
				$('#detailTitle').html(chosenPerfume.product_title);
			});
		});
	</script>
	<style>
		#available_perfumes img {
			width: 55px;
		}
		#available_perfumes aside:first-of-type {
			width: 60px;
		}
		#detail .counter {
			float: right;
		}

		#perfumeDetail img {
			height: 140px;
			display: block;
			margin: 0 auto;
		}
		/* iOS Styles */
		.isiOS .button.add,
		.isDesktopSafari .button.add {
			border: solid 1px #007aff;
			border-radius: 6px;
			height: 30px;
			width: 30px;
			margin-top: 7px;
		}

		.isiOS .button.add::after,
		.isDesktopSafari .button.add::after {
			content: '';
			position: absolute;
			top: 0;
			left: 0;
			display: block;
			height: 28px;
			width: 28px;
		   background-color: #007aff;
		   -webkit-mask-position: 50% 50%;
		   -webkit-mask-size: 90% 90%;
		   -webkit-mask-repeat: no-repeat; 
		   -webkit-mask-image: url('data:image/svg+xml;utf8,<svg version="1.1" id="Layer_2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="48px" height="48px" viewBox="0 0 48 48" enable-background="new 0 0 48 48" xml:space="preserve"> <path d="M36.729,21.049h-4.475h-2.645H28.17c-0.67,0-1.221-0.561-1.221-1.229v-1.623v-1.856v-5.07c0-0.668-0.541-1.21-1.211-1.21 H22.26c-0.668,0-1.21,0.542-1.21,1.21v5.07v2.016v1.464c0,0.668-0.553,1.229-1.222,1.229h-1.688h-2.396h-4.474 c-0.668,0-1.21,0.542-1.21,1.211v3.479c0,0.668,0.542,1.211,1.21,1.211h4.474h2.637h1.447c0.669,0,1.222,0.531,1.222,1.199v1.678 v1.801v5.102c0,0.668,0.542,1.211,1.21,1.211h3.479c0.67,0,1.211-0.543,1.211-1.211v-5.102v-1.84v-1.639 c0-0.668,0.551-1.199,1.221-1.199h1.799h2.285h4.475c0.668,0,1.211-0.543,1.211-1.211V22.26 C37.939,21.591,37.396,21.049,36.729,21.049z"/></svg>');
		 }
		.isiOS #shoppingCart {
			border: solid 1px #007aff;
			border-radius: 6px;
			height: 30px;
			width: 30px;
		}
		.isiOS #shoppingCart::after {
			content: '';
			position: absolute;
			top: 0;
			left: 0;
			display: block;
			height: 28px;
			width: 28px;
		   background-color: #007aff;
		   -webkit-mask-position: 50% 50%;
		   -webkit-mask-size: 90% 90%;
		   -webkit-mask-repeat: no-repeat; 
		   -webkit-mask-image: url('data:image/svg+xml;utf8,<svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="48" height="48" viewBox="0 0 48 48" id="Layer_2" xml:space="preserve"><defs id="defs3149" /> <g id="layer2" style="display:inline"><g transform="translate(-0.08050847,0.40254236)" id="g4059"><path d="M 42.025422,16.038138 38.644067,29.724576 16.423728,29.805086 10.868644,11.529663 4.3474575,10.241527" id="path4061" style="fill:none;stroke:#000000;stroke-width:2.25125003;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" /><path d="m 15.031648,23.042374 24.762973,0" id="path3152" style="fill:none;stroke:#000000;stroke-width:2.25125003;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" /><path d="M 27.614406,30.046611 27.211863,16.843221" id="path3156" style="fill:none;stroke:#000000;stroke-width:2.25125003;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" /><path d="M 21.978813,29.644068 20.04661,16.682204" id="path3158" style="fill:none;stroke:#000000;stroke-width:2.25125003;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" /><path d="M 33.16949,29.805086 34.940677,16.92373" id="path3160" style="fill:none;stroke:#000000;stroke-width:2.25125003;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" /><path d="m 19.805084,31.173729 a 4.0254235,4.1059322 0 1 1 -8.050847,0 4.0254235,4.1059322 0 1 1 8.050847,0 z" transform="matrix(0.62105266,0,0,0.60887512,8.6576239,15.811465)" id="path3162" style="fill:#000000;fill-opacity:1;stroke:#000000;stroke-width:0;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" /><use transform="translate(18.169494,-2.7201237e-8)" id="use3164" x="0" y="0" width="48" height="48" xlink:href="#path3162" /><path d="M 43.071563,16.44115 12.801318,16.52072" id="path4069" style="fill:none;stroke:#000000;stroke-width:2.25125003;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" /></g></g></svg>')
		}
		/* Android Styles */
		.isAndroid .button.back {
			margin-right: 30px;
		}
		.isAndroid .button.back::after {
			content: '';
			display: block;
			height: 30px;
			width: 30px;
			position: absolute;
			top: 0;
			right: -26px;
			background: transparent url(images/perfume-bottle.png) no-repeat center center;
			background-size: 80% auto;
		}
		.isAndroid .button.add,
		.isDesktopChrome .button.add {
			width: 28px;
		}
		.isAndroid .button.add::before,
		.isDesktopChrome .button.add::before {
			content: '';
			position: absolute;
			top: 0;
			left: 0;
			display: block;
			height: 28px;
			width: 28px;
		   background-position: 50% 50%;
		   background-size: 90% 90%;
		   background-repeat: no-repeat; 
		   background-image: url('data:image/svg+xml;utf8,<svg version="1.1" id="Layer_2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="48px" height="48px" viewBox="0 0 48 48" enable-background="new 0 0 48 48" xml:space="preserve"><path d="M36.729,21.049h-4.475h-2.645H28.17c-0.67,0-1.221-0.561-1.221-1.229v-1.623v-1.856v-5.07c0-0.668-0.541-1.21-1.211-1.21  H22.26c-0.668,0-1.21,0.542-1.21,1.21v5.07v2.016v1.464c0,0.668-0.553,1.229-1.222,1.229h-1.688h-2.396h-4.474  c-0.668,0-1.21,0.542-1.21,1.211v3.479c0,0.668,0.542,1.211,1.21,1.211h4.474h2.637h1.447c0.669,0,1.222,0.531,1.222,1.199v1.678  v1.801v5.102c0,0.668,0.542,1.211,1.21,1.211h3.479c0.67,0,1.211-0.543,1.211-1.211v-5.102v-1.84v-1.639  c0-0.668,0.551-1.199,1.221-1.199h1.799h2.285h4.475c0.668,0,1.211-0.543,1.211-1.211V22.26  C37.939,21.591,37.396,21.049,36.729,21.049z"/></svg>');
		}
		.isAndroid #shoppingCart {
			width: 30px;
		}

		.isAndroid #shoppingCart::after {
			content: '';
			position: absolute;
			top: 0;
			left: 0;
			display: block;
			height: 28px;
			width: 28px;
		   background-position: 50% 50%;
		   background-size: 90% 90%;
		   background-repeat: no-repeat; 
		   background-image: url('data:image/svg+xml;utf8,<svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="48" height="48" viewBox="0 0 48 48" id="Layer_2" xml:space="preserve"><defs id="defs3149" /> <g id="layer2" style="display:inline"><g transform="translate(-0.08050847,0.40254236)" id="g4059"><path d="M 42.025422,16.038138 38.644067,29.724576 16.423728,29.805086 10.868644,11.529663 4.3474575,10.241527" id="path4061" style="fill:none;stroke:#000000;stroke-width:2.25125003;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" /><path d="m 15.031648,23.042374 24.762973,0" id="path3152" style="fill:none;stroke:#000000;stroke-width:2.25125003;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" /><path d="M 27.614406,30.046611 27.211863,16.843221" id="path3156" style="fill:none;stroke:#000000;stroke-width:2.25125003;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" /><path d="M 21.978813,29.644068 20.04661,16.682204" id="path3158" style="fill:none;stroke:#000000;stroke-width:2.25125003;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" /><path d="M 33.16949,29.805086 34.940677,16.92373" id="path3160" style="fill:none;stroke:#000000;stroke-width:2.25125003;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" /><path d="m 19.805084,31.173729 a 4.0254235,4.1059322 0 1 1 -8.050847,0 4.0254235,4.1059322 0 1 1 8.050847,0 z" transform="matrix(0.62105266,0,0,0.60887512,8.6576239,15.811465)" id="path3162" style="fill:#000000;fill-opacity:1;stroke:#000000;stroke-width:0;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" /><use transform="translate(18.169494,-2.7201237e-8)" id="use3164" x="0" y="0" width="48" height="48" xlink:href="#path3162" /><path d="M 43.071563,16.44115 12.801318,16.52072" id="path4069" style="fill:none;stroke:#000000;stroke-width:2.25125003;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" /></g></g></svg>')
		}
		/* Windows Styles */
		.isWindows .toolbar .button {
			padding: 0 !important;
		}
		.isWindows .button.add.align-flush {
			top: 8px !important;
		}
		.isWindows .button.add::before {
			content: '\E109';
			display: block;
			height: 30px;
			width: 30px;
			color: #fff;
			font-family: "Segoe UI Symbol";
			font-weight: normal;
			font-size: 14pt;
			text-align: left;
			display: block;
			margin: 10px 0 0 5px;
		}
		.isWindows .buttonhover::before {
			color: #000 !important;
		}
		.isWindows #shoppingCart::before {
			content: '\E14D';
			display: block;
			height: 30px;
			width: 30px;
			color: #fff;
			font-family: "Segoe UI Symbol";
			font-weight: normal;
			font-size: 14pt;
			text-align: left;
			display: block;
			margin: 10px 0 0 5px;
		}
	</style>
</head>
<body>
	<nav class='current'>
		<h1 id='mainTitle'>Perfumes</h1>
	</nav>
	<article id='main' class='current'>
		<section>
			<ul class='list' id='perfumeGenres'></ul>
		</section>
	</article>
	<nav class='next'>
		<a href='#' class='button back'>Perfumes</a>
		<h1 id='perfumesGenreTitle'>Perfumes</h1>
	</nav>
	<article class='next' id='perfumeList'>
		<section>
			<ul class='list' id='available_perfumes'></ul>
		</section>
	</article>

	<nav class='next'>
		<a href='#' class='button back' id='backToGenre'>Back</a>
		<h1 id='detailTitle'>Detail</h1>
	</nav>
	<article class='next' id='detail'>
		<section>
			<ul class='list' id='perfumeDetail'></ul>
		</section>
	</article>
	<div class='toolbar next'>
		<a href="javascript:void(null)" id="shoppingCart" class="button"></a>
		<a href="javascript:void(null)" class="button add align-flush"></a>
	</div>
</body>
</html>
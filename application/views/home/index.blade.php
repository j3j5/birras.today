<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
	<head>
		<title>Birras TODAY...WHERE?</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<!-- Piwik -->
		<script type="text/javascript">
			var _paq = _paq || [];
			_paq.push(["setDocumentTitle", document.domain + "/" + document.title]);
			 _paq.push(["setCookieDomain", "*.birras.today"]);
			_paq.push(["setDomains", ["*.birras.today"]]);
			_paq.push(['trackPageView']);
			_paq.push(['enableLinkTracking']);
			(function() {
				var u=(("https:" == document.location.protocol) ? "https" : "http") + "://dezwartepoet.nl/piwik/";
				_paq.push(['setTrackerUrl', u+'piwik.php']);
				_paq.push(['setSiteId', 2]);
				var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0]; g.type='text/javascript';
				g.defer=true; g.async=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
			})();
		</script>
		<noscript><p><img src="http://dezwartepoet.nl/piwik/piwik.php?idsite=2" style="border:0;" alt="" /></p></noscript>
		<!-- End Piwik Code -->

		<style>
			body {
				margin: 0;
				padding: 0;
				font-family: sans-serif;
				color: white;
				background-color:  #E9573F;
				text-align: center;
			}
			h1 {
				display: inline-block;
				margin-top: 50px;
				color: white;
				border-top: solid 5px white;
				border-bottom: solid 5px white;
				transform: skew(0, -5deg);
			}
			section {
				background-color: #FC6E51;
			}
			#action {
				margin: 0 auto;
				width: 700px;
				position: relative;
				height: 300px;
			}

			#keg {
				position: absolute;
				height: 200px;
				width: 70px;
				background-color: #656D78;
				border-right: solid 20px #434A54;
				bottom: 0;
				left: 310px;
			}
			#pipe {
				position: absolute;
				height: 30px;
				width: 10px;
				top: 30px;
				left: 10px;
				background-color: #CCD1D9;
			}

			#pipe:before {
				position: absolute;
				display: block;
				content: " ";
				height: 20px;
				width: 30px;
				top: -5px;
				left: 5px;
				background: linear-gradient(to bottom, #CCD1D9 50%, #AAB2BD 50%);
				border-radius: 0 10px 10px 0;
			}

			#pipe:after {
				position: absolute;
				display: block;
				content: " ";
				width: 10px;
				background-color: rgba(255, 206, 84, 0.5);

				animation: flow 5s ease infinite;
			}

			@keyframes flow {
				0%, 15% {
					top: 30px;
					height: 0px;
				}
				20% {
					height: 125px;
				}
				40% {
					top: 30px;
					height: 85px;
				}
				55% {
					top: 30px;
					height: 60px;
				}
				60%, 100% {
					top: 70px;
					height: 0px;
				}
			}

			#pipe-front {
				position: absolute;
				height: 14px;
				width: 14px;
				top: 25px;
				left: 5px;
				background-color: #F5F7FA;
				border-radius: 10px;
				border: solid 3px #CCD1D9;
			}

			#pipe-handle {
				transform-origin: center bottom;
				position: absolute;
				width: 0;
				height: 5px;
				top: -20px;
				left: 5px;
				border-style: solid;
				border-width: 50px 10px 0 10px;
				border-color: black transparent transparent transparent;

				animation: handle 5s ease infinite;
			}
			#pipe-handle:before {
				position: absolute;
				top: -60px;
				left: -10px;
				display: block;
				content: ' ';
				width: 20px;
				height: 10px;
				background-color: #CCD1D9;
				border-radius: 5px 5px 0 0;
			}
			#pipe-handle:after {
				position: absolute;
				top: -20px;
				left: -5px;
				display: block;
				content: ' ';
				width: 10px;
				height: 20px;
				background-color: #CCD1D9;
			}
			@keyframes handle {
				0%, 10% {
					transform: rotate(0deg);
				}
				20%, 50% {
					transform: rotate(-90deg);
				}
				60%, 100% {
					transform: rotate(0deg);
				}
			}

			.glass {
				position: absolute;
				height: 100px;
				width: 70px;
				bottom: 0;
				background-color: rgba(255, 255, 255, 0.0);
				border-radius: 5px;

				animation: slide 5s ease forwards infinite;
			}

			@keyframes slide {
				0% {
					opacity: 0;
					left: 0;
				}
				20%, 80% {
					opacity: 1;
					left: 300px;
				}
				100% {
					opacity: 0;
					left: 600px;
				}
			}

			.front-glass {
				position: relative;
				height: 100%;
				width: 100%;
				background-color: rgba(255, 255, 255, 0.3);
				border-radius: 5px;
			}

			.beer {
				position: absolute;
				bottom: 15px;
				margin: 0 5px;
				/*height: 80%;*/
				width: 60px;
				background-color: rgba(255, 206, 84, 0.8); /* #FFCE54*/
				border-radius: 0 0 5px 5px;
				border-top: solid 0px rgba(255, 206, 84, 0.8);

				animation: fillup 5s ease infinite;
			}
			.beer:after {
				position: absolute;
				display: block;
				content: " ";
				/*height: 20px;*/
				width: 100%;
				background-color: white;
				/*top: -20px;*/
				border-radius: 5px 5px 0 0;

				animation: fillupfoam 5s linear infinite, wave 0.5s alternate infinite;
			}

			.handle {
				position: absolute;
				right: -20px;
				top: 20px;
			}
			.handle .top-right {
				height: 20px;
				width: 10px;
				border-top: solid 10px rgba(255, 255, 255, 0.4);
				border-right: solid 10px rgba(255, 255, 255, 0.4);
				border-top-right-radius: 20px;
			}
			.handle .bottom-right {
				height: 20px;
				width: 10px;
				border-bottom: solid 10px rgba(255, 255, 255, 0.4);
				border-right: solid 10px rgba(255, 255, 255, 0.4);
				border-bottom-right-radius: 20px;
			}

			@keyframes fillup {
				0%, 20% {
					height: 0px;
					border-width: 0px;
				}
				40% {
					height: 40px;
				}
				80%, 100% {
					height: 80px;
					border-width: 5px;
				}
			}
			@keyframes fillupfoam {
				0%, 20% {
					top: 0px;
					height: 0px;
				}
				60%, 100% {
					top: -14px;
					height: 15px;
				}
			}
			@keyframes wave {
				from {
					transform: skew(0, -3deg);
				}
				to {
					transform: skew(0, 3deg);
				}
			}
		</style>

	</head>
	<body>
 		<script type="text/javascript" src="http://leaverou.github.io/prefixfree/prefixfree.min.js"></script>

		<section>
			<div id="action">
				<div id="keg">
					<div id="pipe-handle"></div>
					<div id="pipe"></div>
					<div id="pipe-front"></div>
				</div>

				<div class="glass">
					<div class="beer"></div>
					<div class="handle">
						<div class="top-right"></div>
						<div class="bottom-right"></div>
					</div>
					<div class="front-glass"></div>
				</div>
			</div>
		</section>

		<h1>
			BIRRAS.TODAY
		</h1>

<!--		<h3>TODAY, 2014/07/03 <a href="https://www.facebook.com/events/1427632507513612/">II Concurso de Tapas revindicativas</a> at 18:00</h3> -->
		<!--
		<h2>YES,</h2>
		<h3>TODAY, 2014/07/10 around 18:30</h3>
		<a href="http://www.pacificparc.nl/" ><h3>Pacific Park at the Westerpark</a></h3>
		<p>Address is:
		<a href="https://www.google.com/maps/place/Pacific+Parc/@52.385349,4.871705,15z/data=!4m2!3m1!1s0x0:0xd873b3fffa56cc6?hl=en-US">Polonceaukade 23
		Cultuurpark Westergasfabriek
		1014 DA Amsterdam Netherlands</a></p>
		-->
		<h2>NOT KNOWN YET</h2>
		<h3>GO TO SKYPE AND GIVE YOUR OPINION!!</h3>

	</body>
</html>


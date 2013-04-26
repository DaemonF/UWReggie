		<p id="feedback" class="center">Found a bug? Want a new feature? Let me know at <a href="mailto:uwreggie@gmail.com">uwreggie@gmail.com</a></p>
		<div class="center">
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="hosted_button_id" value="27FUNH822EZXG">
				<input style="border: none; width: auto;" type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
				<!--<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">-->
			</form>
		</div>
		</div><!--#wrapper-->
		<div id="footer">
			UWReggie is in no way affiliated with or endorsed by the
			University of Washington.
		</div><!--#footer-->
		<script type="text/javascript">
			(function() {
				function getScript(url,success){
					var script=document.createElement('script');
					script.src=url;
					var head=document.getElementsByTagName('head')[0],
					done=false;
					script.onload=script.onreadystatechange = function(){
						if ( !done && (!this.readyState || this.readyState == 'loaded' || this.readyState == 'complete') ) {
							done=true;
							success();
							script.onload = script.onreadystatechange = null;
							head.removeChild(script);
						}
					};
					head.appendChild(script);
				}
				getScript("//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js",function(){
					getScript("scripts/reggie.js",function(){
					});
				});
		  })();
    </script>
		<script type="text/javascript">
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-29369308-1']);
			_gaq.push(['_trackPageview']);

			(function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = 'scripts/ga.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
		</script>
	</body>
</html>

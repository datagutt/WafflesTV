(function(global){
	if(jessie && jessie.isHostMethod && jessie.isHostMethod(Waffles, 'addToQueue')){
		Waffles.addToQueue(function(){
			if(jessie && jessie.areFeatures('query', 'getElement', 'forEach', 'attachListener', 'cancelDefault', 'createXhr', 'xhrSend')){
				var show = window.location.pathname.split('/')[3];
				var downloadButtons = jessie.query('.download');
				var streamLinks = jessie.query('.stream');
				var player = flowplayer('player', {
					src: '/flowplayer.swf',
					width: '70%',
					height: 400
				});
				var playerEl = jessie.getElement(player.id());
				playerEl.style.display = 'none';
				jessie.forEach(streamLinks, function(link){
					jessie.attachListener(link, 'click', function(e){									if(playerEl.style.display == 'none'){
							playerEl.style.display = 'block';
						}
						player.setClip(link.href);
						player.play();
						jessie.cancelDefault(e);
					});
				});
				jessie.forEach(downloadButtons, function(button){
					jessie.attachListener(button, 'click', function download(e){
						var season = button.getAttribute('data-season');
						var episode = button.getAttribute('data-number');
						jessie.xhrSend(jessie.createXhr(), '/api/download/' + show + '/' + season + '/' + episode, {
							success: function(status){
								if(status && status.responseText){
									if(JSON && jessie.isHostMethod(JSON, 'parse')){
										var data = JSON.parse(status.responseText);
									}else{
										var data = eval('(' + status.responseText + ')');
									}
									alert(data.message);
								}
							}
						});
						jessie.cancelDefault(e);
					});
				});
			}
		});
	}
})(window);
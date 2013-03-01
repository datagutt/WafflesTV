(function(global){
	var Waffles = {};
	Waffles.queue = []; 
	Waffles.init = function(){
		/* 
			General initalization function,
			the rest should be added to the queue.
		*/
		window.scrollTo(0, 1);
	};
	Waffles.addToQueue = function(script){
		var queue = Waffles.queue;
		if(jessie && jessie.isHostMethod && jessie.isHostMethod(queue, 'push')){
			if(queue.indexOf(script) == -1){
				queue.push(script);
			}
		}
	};
	if(jessie && jessie.areFeatures && jessie.areFeatures('attachListener', 'forEach')){
		jessie.attachListener(window, 'load', function ready(){
			Waffles.init();
			jessie.forEach(Waffles.queue, function(script){
				if(typeof script == 'function'){
					script();
				}
			});
		});
	}
	global.Waffles = Waffles;
})(window);
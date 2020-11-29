	
	document.onreadystatechange = function() {
		if (document.readyState === "complete") {
			var laZonePages = new purePajinate({ 
				containerSelector: '.zonePagesCMD .items', 
				itemSelector: '.zonePagesCMD .items > div', 
				navigationSelector: '.zonePagesCMD .page_navigation',
				wrapAround: true,
				pageLinksToDisplay: 10,
				itemsPerPage: 1,
				startPage: 0
			});
		}
	};
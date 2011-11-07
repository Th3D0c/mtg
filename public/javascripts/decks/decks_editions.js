$(document).ready(function() {
	$("input#card").autocomplete({
		source : '/decks/getCardsNames'
	});

	$('ul#cover_flow').roundabout({
		reflect : true,
        btnNext: '#next',
        btnPrev: '#previous'
	});

});
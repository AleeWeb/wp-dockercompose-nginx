//
// Admin page match management
//

(function ($) {

	function ArcaneMatchMap(id, game_id, map_id) {

		var othis = this;

		this.id = id;
		this.map_id = typeof(map_id) != 'undefined' ? parseInt(map_id) : 0;
		this.game_id = game_id;
		this.mapSite = $('#mapsite');
		this.rounds = {};
		this.round_ID = 0;
		this.mapElement = $('<div class="map">\n\
						<div class="leftcol">\n\
							<img alt="img" src="' + wpCWL10n.noMapImg+'" class="screenshot" />\n\
							<select name="scores[' + this.id + '][map_id]" class="map-select" disabled="disabled"></select>\n\
						</div>\n\
						<div class="rightcol">\n\
						<div class="team1_title">\n\
						'+$('.single-matches .team1-title').text()+'\n\
						</div>\n\
						<div class="team2_title">\n\
						'+$('.single-matches .team2-title').text()+'\n\
						</div>\n\
						<div class="add-round">\n\
							<input type="button" class="btn" value="' + wpCWL10n.addRound + '" />\n\
						</div>\n\
						<div class="clear"></div>\n\
						<a title="' + wpCWL10n.excludeMap + '" class="remove remove-matchmap rem_right"><i class="fas fa-times"></i></a>\n\
						</div>\n\
					</div>');

		this.remove = function() {
			this.mapElement.remove();
		}

		this.removeRound = function(i) {
			this.rounds[i].remove();
			delete this.rounds[i];
		}

		this.addRound = function(score1, score2, round_id) {

			if(typeof(round_id) == 'undefined')
				round_id = 0;

			var x = ++this.round_ID;
			var n = $('<div class="round">\n\
							<input type="text" name="scores[' + this.id + '][team1][]" class="small-text roundval" value="0" />\n\
							<input type="text" name="scores[' + this.id + '][team2][]" class="small-text roundval" value="0" />\n\
							<input class="roundval" type="hidden" name="scores[' + this.id + '][round_id][]" value="' + round_id + '" />\n\
							<a title="' + wpCWL10n.removeRound + '" class="remove"><i class="fas fa-times"></i></a>\n\
						</div>');
			var i = n.find('input');

			i.eq(0).val(score1); i.eq(1).val(score2);

			n.insertBefore(this.mapElement.find('.add-round'));

			this.rounds[x] = n;

			n.find('.remove').on('click', function(e) {
			   othis.removeRound(x);
			   return false;
			});

			return x;
		}

		this.mapElement.find('.remove-matchmap')
			.on('click', function(e) {
				othis.remove();
				return false;
			});

		// load map list

		jQuery.post(ajaxurl, {
				action: 'get_maps',
				game_id: this.game_id
			}, function(json) {

				var select = othis.mapElement.find('.map-select');

				if(json.length == 0){
					select.remove();
				}

				for(var i = 0; i < json.length; i++) {
					select.append($('<option></option>')
							.attr('rel', json[i].screenshot_url)
							.val(json[i].id)
							.text(json[i].title));

				}

				select.change(function(){
					var option = $(this).find('option:selected');
					var src = option.attr('rel');

					if(src.length < 1)
						src = wpCWL10n.noMapImg;

					othis.mapElement.find('.screenshot').attr('src', src);
					othis.mapElement.find('.title span').text(option.text());

					othis.map_id = option.val();
				});

				select.removeAttr('disabled');

				if(othis.map_id > 0) {
					select.find('option[value=' + othis.map_id + ']')
						.attr('selected', 'selected').trigger('change');
				} else {
					select.find('option:first').trigger('change');
				}
		}, 'json');

		this.mapElement.find('.add-round input').bind('click', this.mapElement,
			function(evt){
				othis.addRound();
				$(this).blur();
			});

		this.mapSite.append(this.mapElement);
	}

	function matchManager() {

		this.last_id = wpCWL10n.now * -1;
		this.matchSite = $('#matchsite');
		this.maps = {};

		this.addMap = function (field_id, map_id) {
			if(typeof(field_id) == 'undefined')
				field_id = --this.last_id;

			this.maps[field_id] = new ArcaneMatchMap(field_id, $('#game_id').val(), map_id);

			return this.maps[field_id];
		}

		this.remove = function (id) {
			if(typeof(this.maps[id]) == 'object')
				this.maps[id].remove();
				delete this.maps[id];
		}

		this.removeAll = function () {
			for(var i in this.maps) {
				this.maps[i].remove();
			}

			this.maps = {};
		}
	}

	var wpMatchManager = new matchManager();
	window.wpMatchManager = wpMatchManager;

	$(document).ready(function ($) {
		$('#game_id').change(function(){
			wpMatchManager.removeAll();
		});

		$('#wp-cw-addmap input').on('click', function(e) {
			var m = wpMatchManager.addMap();

			// add two rounds by default
			m.addRound(0, 0);
			m.addRound(0, 0);

			$(this).blur();
		});

		$('#matchsite [name=team2]').change(function () {
			$('#new_team_title').val('');
		});
	});

})(jQuery);
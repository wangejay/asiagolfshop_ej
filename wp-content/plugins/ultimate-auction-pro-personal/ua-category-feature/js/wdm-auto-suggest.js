jQuery(document).ready(function($){
    
        var ajaxurl = wdm_ua_obj_sr.ajxurl;
        
        var data = {
		action:'auction_stags'
	    };
	    $.post(ajaxurl, data, function(tags) {
    
        $('#ua_wdm_auction_search').autocomplete({
        lookup: JSON.parse(tags),
        minChars: 1,
        lookupFilter: function(suggestion, originalQuery, queryLowerCase) {
            var re = new RegExp('\\b' + $.Autocomplete.utils.escapeRegExChars(queryLowerCase), 'gi');
            return re.test(suggestion.value);
        }
    });
            $('.wdm-autocomplete-suggestion').live('click', function(){$('#ua_wdm_search_btn').trigger('click');});
            
            if ($('#wdm_ua_auc_avail').val() == "" || Number($('#wdm_ua_auc_avail').val()) <= 0)
                $('.wdm_ua_search_res_text span').html('0');
            else
                $('.wdm_ua_search_res_text span').html($('#wdm_ua_auc_avail').val());
	});
});
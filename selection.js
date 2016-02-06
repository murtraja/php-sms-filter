            var clickHandler = function(row){
                row.toggleClass('highlight');
                row.trigger("bgChange");
            }
            $('tbody#recipientTableBody').on('click', 'tr', function(){
                clickHandler($(this));
            });
            $('tbody#recipientTableBody').on('mouseenter', 'tr', 
                function(evt){
                    if(evt.ctrlKey)
                    {
                        clickHandler($(this));
                    }
                });
            $('#b_toggle').click(function(){
                $.each($('tbody#recipientTableBody tr'), function(){
                    clickHandler($(this));
                    
                });
            });
            $('#b_all').click(function(){
                $.each($('tbody#recipientTableBody tr'), function(){
                    $(this).addClass("highlight");
                    $(this).trigger("bgChange");
                });
            });
            $("#b_none").click(function(){
                $.each($('tbody#recipientTableBody tr'), function(){
                    $(this).removeClass("highlight");
                    $(this).trigger("bgChange");
                });
            });
            $('tbody#recipientTableBody').on("bgChange", "tr", function(){
                var selectedRecords = $('tbody#recipientTableBody tr.highlight');
                var len = selectedRecords.length;
                var selectionStatusString = "Selected "+len+" record(s).";
                $('#selection_status').html(selectionStatusString);
            });
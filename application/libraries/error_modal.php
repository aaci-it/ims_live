
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Error_modal {
    public function modal($title){
        echo '<div class="modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                            <h4 class="modal-title"></h4>
                        </div>
                        <div class="modal-body">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                           <!--<button type="button" class="btn btn-danger">Save changes</button>-->
                        </div>
                    </div>
                </div>
            </div>';

            echo '
            <script>
             $(function(){
                window.alert = function(msg) { // for rewriting the allert
                        $(".modal-title").html("'.$title.'"); // assigning modal title from parameter
                        $(".modal-body").html("<p>"+msg+"</p>"); // msg in modal body
                        $(".modal").modal("show"); // show modal instead alert box
                    }
            });
            </script>';
    }

    public function alert($title, $msg){
        $this->modal($title); //passing pop up title to modal function
        echo '<script>$(function(){alert('.json_encode($msg).')});</script>'; // json_encode msg otherwise jquery will throw error
    }
}
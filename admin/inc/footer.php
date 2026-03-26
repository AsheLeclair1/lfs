<script>
$(document).ready(function(){
    window.viewer_modal = function($src = ''){
        start_loader();
        var t = $src.split('.');
        t = t[1];
        if(t =='mp4'){
            var view = $("<video src='"+$src+"' controls autoplay></video>");
        }else{
            var view = $("<img src='"+$src+"' />");
        }
        $('#viewer_modal .modal-content video,#viewer_modal .modal-content img').remove();
        $('#viewer_modal .modal-content').append(view);
        $('#viewer_modal').modal({
            show:true,
            backdrop:'static',
            keyboard:false,
            focus:true
        });
        end_loader();
    };
    
    window.uni_modal = function($title = '' , $url='',$size=""){
        start_loader();
        $.ajax({
            url:$url,
            error:function(err){
                console.log(err);
                alert("Произошла ошибка");
            },
            success:function(resp){
                if(resp){
                    $('#uni_modal .modal-title').html($title);
                    $('#uni_modal .modal-body').html(resp);
                    if($size != ''){
                        $('#uni_modal .modal-dialog').addClass($size+'  modal-dialog-centered');
                    }else{
                        $('#uni_modal .modal-dialog').removeAttr("class").addClass("modal-dialog modal-md modal-dialog-centered");
                    }
                    $('#uni_modal').modal({
                        show:true,
                        backdrop:'static',
                        keyboard:false,
                        focus:true
                    });
                    end_loader();
                }
            }
        });
    };
    
    window._conf = function($msg='',$func='',$params = []){
        $('#confirm_modal #confirm').attr('onclick',$func+"("+$params.join(',')+")");
        $('#confirm_modal .modal-body').html($msg);
        $('#confirm_modal').modal('show');
    };
});
</script>

<footer id="footer" class="footer">
    <div class="copyright">
        &copy; <?= date('Y') ?> Система учета потерянных и найденных вещей
    </div>
</footer>

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- JS файлы -->
<script src="<?= base_url ?>assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="<?= base_url ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url ?>assets/vendor/chart.js/chart.umd.js"></script>
<script src="<?= base_url ?>assets/vendor/echarts/echarts.min.js"></script>
<script src="<?= base_url ?>assets/vendor/quill/quill.min.js"></script>
<script src="<?= base_url ?>assets/vendor/tinymce/tinymce.min.js"></script>
<script src="<?= base_url ?>assets/vendor/php-email-form/validate.js"></script>

<!-- Основной JS файл -->
<script src="<?= base_url ?>assets/js/main.js"></script>

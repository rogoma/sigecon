<!-- @push('scripts')
<script type="text/javascript">
$(document).ready(function(){

    getNotifications = function(){
        // obtenemos primeramente el token csrf y luego realizamos
        // la solicitud de notificaciones
        $.ajax({
            url : '/token/',
            method : 'GET',
            success: function(response){
                try{
                    if(response.status == "success"){
                        // INICIO obtener las notificaciones
                        $.ajax({
                            url : '/contracts/getNotifications/',
                            method : 'GET',
                            data: { _token: response.token },
                            success: function(data){
                                try{
                                    if(data.status == "success"){
                                        notificaciones = '<li style="font-size: 18px;color:red;background-color:yellow"><h5>VENCIMIENTOS DE PÓLIZAS</h5></li>';
                                        parent = document.getElementById('alertas-notificaciones');
                                        alertas = 0;

                                        notificaciones += '<p><a style="font-size: 18px;color:BLUE" target="_blank" href="/pdf/panel_contracts5">REPORTE VCTOS PDF</a></p>'
                                        // notificaciones += '<p><a style="font-size: 18px;color:BLUE" target="_blank" href="/pdf/panel_contracts9">REPORTE PRUEBA</a></p>'

                                        // notificaciones pólizas anticipos
                                        if(data.alerta_advance.length > 0){
                                            data.alerta_advance.forEach(element => {
                                                alertas += 1;
                                            });
                                        }

                                        if(data.alerta_advance.length > 0){
                                            // if(data.alerta_advance.length > 0 || data.alerta_fidelity.length > 0){
                                            $('#numero-notificaciones').text(alertas);
                                            parent.innerHTML = notificaciones;
                                        }
                                    }else{
                                        console.log(data.message);
                                    }
                                }catch(error2){
                                    console.log(error2);
                                }
                            },
                            error: function(error2){
                                console.log(error2);
                            }
                        });
                        // FIN obtener las notificaciones
                    }
                }catch(error){
                    console.log(error);
                }
            }
        });
    }

    getNotifications();

    // intervalo cada 10 minutos
    setInterval(getNotifications, 10*60*1000);
});
</script>
@endpush -->
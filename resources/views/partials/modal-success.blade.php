<form action="#" id="success_form" method="GET" class="success_form">
    {{ csrf_field() }}
    <div class="modal modal-success fade" data-backdrop="static" id="success-modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #46be8a;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar" style="color: white;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" style="color: #ffffff !important">
                        <i class="fa-solid fa-circle-check"></i> Confirmar Entrega
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="text-center" style="text-transform: uppercase;">
                        <div style="font-size: 5em; color: #46be8a; margin-bottom: 15px;">
                            <i class="fa-solid fa-cart-shopping"></i>
                        </div>
                        <h4 style="margin-top: 0; color: #0c0c0c;">
                            <strong>¿CONFIRMAR ENTREGA?</strong>
                        </h4>
                    </div>
                    <label class="checkbox-inline">
                        <input type="checkbox" required>Confirmar..!
                    </label>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <input type="submit" class="btn btn-success btn-form-submit" value="Sí, Entregar">
                </div>
            </div>
        </div>
    </div>
</form>
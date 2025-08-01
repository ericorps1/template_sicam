</div> <!-- container -->
        </div> <!-- content -->


        <!-- ============================================================================ -->
        <!-- FOOTER DEL SISTEMA -->
        <!-- ============================================================================ -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <span class="letraSicam letraPequena">
                            <script>
                                document.write(new Date().getFullYear());
                            </script> 
                            <a href="">♥ AHJ ENDE &copy;  <?php if( $tipoUsuario == 'Ejecutivo' ): echo 'S.I.C.A.M. - SISTEMA DE INFORMACIÓN COMERCIAL, ADMINISTRATIVO Y DE MENTORÍA'; endif; ?></a>
                        </span>
                    </div>
                    <div class="col-md-6">
                        <!-- Espacio reservado para información adicional -->
                    </div>
                </div>
            </div>
        </footer>

        </div>
        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->
        </div>
        <!-- END wrapper -->


        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <!-- ============================================================================ -->
        <!-- LIBRERÍAS JAVASCRIPT PRINCIPALES -->
        <!-- ============================================================================ -->
        
        <!-- Librerías base -->
        <script src="assets/libs/jquery/jquery.min.js"></script>
        <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/libs/simplebar/simplebar.min.js"></script>
        <script src="assets/libs/node-waves/waves.min.js"></script>
        <script src="assets/libs/waypoints/lib/jquery.waypoints.min.js"></script>
        <script src="assets/libs/jquery.counterup/jquery.counterup.min.js"></script>
        <script src="assets/libs/feather-icons/feather.min.js"></script>

        <!-- JSTree para estructuras jerárquicas -->
        <script src="assets/libs/jstree/jstree.min.js"></script>

        <!-- Script principal de la aplicación -->
        <script src="assets/js/app.min.js"></script>

        <!-- ============================================================================ -->
        <!-- LIBRERÍAS ESPECIALIZADAS -->
        <!-- ============================================================================ -->
        
        <!-- Handsontable para manejo de datos tabulares -->
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.js"></script>
        <script src="../ejecutivo/assets/hansontable/es-MX.js"></script>

        <!-- Manejo de fechas y calendarios -->
        <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/pikaday@1.8.2/pikaday.min.js"></script>

        <!-- Componentes de selección -->
        <script src="assets/libs/selectize/js/standalone/selectize.min.js"></script>
        <script src="assets/libs/mohithg-switchery/switchery.min.js"></script>
        <script src="assets/libs/multiselect/js/jquery.multi-select.js"></script>
        <script src="assets/libs/select2/js/select2.min.js"></script>

        <!-- Sweet Alerts para alertas elegantes -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

        <!-- ============================================================================ -->
        <!-- DATATABLES - Manejo de tablas interactivas -->
        <!-- ============================================================================ -->
        <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
        <script src="assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
        <script src="assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
        <script src="assets/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/buttons.flash.min.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
        <script src="assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
        <script src="assets/libs/datatables.net-select/js/dataTables.select.min.js"></script>
        <script src="assets/libs/pdfmake/build/pdfmake.min.js"></script>
        <script src="assets/libs/pdfmake/build/vfs_fonts.js"></script>

        <!-- Inicialización de DataTables -->
        <script src="assets/js/pages/datatables.init.js"></script>

        <!-- ============================================================================ -->
        <!-- LIBRERÍAS ADICIONALES -->
        <!-- ============================================================================ -->
        
        <!-- Toastr para notificaciones -->
        <script src="assets/libs/toastr/build/toastr.min.js"></script>

        <!-- Dropify para subida de archivos -->
        <script src="assets/libs/dropzone/min/dropzone.min.js"></script>
        <script src="assets/libs/dropify/js/dropify.min.js"></script>

        <!-- FullCalendar para calendarios -->
        <script src="assets/libs/moment/min/moment.min.js"></script>
        <script src="assets/libs/fullcalendar/main.min.js"></script>

        <!-- JODIT Editor de texto -->
        <script src="../js/jodit.min.js"></script>

        <!-- ============================================================================ -->
        <!-- LOADER -->
        <!-- ============================================================================ -->
        <script>
            $(document).ready(function() {
                // Crear loader con tu estructura
                $('body').prepend(`
                    <div id="loader">
                        <div class="spinner-border avatar-lg text-primary m-2" role="status"></div>
                        <span class="letraSicam">SICAM</span>
                    </div>
                `);
                
                // Ocultar loader después de 2 segundos
                // setTimeout(function() {
                //     $('#loader').addClass('hidden');
                // }, 2000);
            });
        </script>

        <!-- ============================================================================ -->
        <!-- F LOADER -->
        <!-- ============================================================================ -->


        <!-- ============================================================================ -->
        <!-- JQUERY GLOBAL -->
        <!-- ============================================================================ -->


        <!-- ============================================================================ -->
        <!-- F JQUERY GLOBAL -->
        <!-- ============================================================================ -->

    </body>
</html>
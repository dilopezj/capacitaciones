<!--CONTENT-->
<div class="container-fluid">
    <a class="btn btn-link btn-primary" href="archivos/preguntas.xlsx" download><i class="fas fa-file"></i> &nbsp;
        Formato Excel Preguntas</a>
    <form id="formArchivoPreguntas" method="post" enctype="multipart/form-data">
        <fieldset>
            <legend><i class="far fa-file-excel"></i> &nbsp;Cargar archivo de Preguntas</legend>
            <div class="container-fluid">
                <div class="form-group">
                    <label for="archivo">Seleccionar archivo:</label>
                    <input type="file" class="form-control form-control-file" id="archivoPreguntas"
                        name="archivoPreguntas" accept=".xlsx,.csv">
                </div>
                <button type="button" class="btn btn-primary" onclick="cargarPreguntas()">Cargar
                    Archivo</button>
            </div>
        </fieldset>
    </form>
    <form id="formArchivoRespuestas" method="post" enctype="multipart/form-data">
        <a class="btn btn-link btn-primary" href="archivos/respuestas.xlsx" download><i class="fas fa-file"></i> &nbsp;
            Formato Excel Respuestas</a>
        <fieldset>
            <legend><i class="far fa-file-excel"></i> &nbsp;Cargar archivo de Respuestas</legend>
            <div class="container-fluid">
                <div class="form-group">
                    <label for="archivo">Seleccionar archivo:</label>
                    <input type="file" class="form-control form-control-file" id="archivoRespuestas" name="archivoRespuestas"
                        accept=".xlsx,.csv">
                </div>
                <button type="button" class="btn btn-primary" onclick="cargarRespuestas()">Cargar
                    Archivo</button>
            </div>
        </fieldset>
    </form>
</div>
<div class="divgeneral clasesmodal" id="1" style="display: none;">
    <form action="{{ route('store', ['type' => 'general']) }}" method="POST">
        @csrf
        <ul>
            <li>
                <label for="general_identifier">Identificador general<span class="text-red-500">*</span></label>
                <input type="text" name="general_identifier" maxlength="250" required>
            </li>
            <li>
                <label for="general_heading">Titulo general<span class="text-red-500">*</span></label>
                <input type="text" name="general_heading" maxlength="250" required>
            </li>
            <li>
                <label for="subtitle">Subtitulo</label>
                <input type="text" name="subtitle" maxlength="250">
            </li>
            <li>
                <label for="general_catalog">Catalogo general<span class="text-red-500">*</span></label>
                <input type="text" name="general_catalog" maxlength="250" required>
            </li>
            <li>
                <label for="general_admission">Entrada general<span class="text-red-500">*</span></label>
                <input type="text" name="general_admission" maxlength="250" required>
            </li>
            <li>
                <label for="language">Idioma<span class="text-red-500">*</span></label><br>
                <select id="language" name="language" class="block w-full mt-2 p-2 border rounded" required>
                    <option value="" disabled selected>Seleccionar</option>
                    <option value="eng">Inglés</option>
                    <option value="spa">Español</option>
                    <option value="fra">Francés</option>
                    <option value="deu">Alemán</option>
                    <option value="ita">Italiano</option>
                    <option value="zho">Chino</option>
                    <option value="jpn">Japonés</option>
                    <option value="rus">Ruso</option>
                    <option value="ara">Árabe</option>
                    <option value="por">Portugués</option>
                </select>
            </li>
            <li>
                <label for="description">Descripción<span class="text-red-500">*</span></label>
                <textarea name="description" id="description" rows="8" maxlength="350" required></textarea>
            </li>
            <li>
                <label for="keywords">Palabras clave<span class="text-red-500">*</span></label>
                <input type="text" name="keywords" maxlength="250" required>
            </li>
            <li>
                <label for="coverage">Cobertura</label>
                <button type="button" class="dropdownButton buttondropdown">
                    Mostrar/Ocultar Inputs
                </button>
                <div class="dropdownContent dropdown-content mt-2">
                    <input type="text" name="coverage_name_of_the_period" class="block w-full mt-2 p-2 border rounded" placeholder="Nombre del periodo historico">
                    <input type="text" name="coverage_classification_scheme_1" class="block w-full mt-2 p-2 border rounded" placeholder="Esquema de clasificación">
                    <input type="text" name="coverage_time" class="block w-full mt-2 p-2 border rounded" placeholder="Momento en el tiempo">
                    <input type="text" name="coverage_classification_scheme_2" class="block w-full mt-2 p-2 border rounded" placeholder="Esquema de clasificación"><br>
                    <label for="coverage_location">Jerarquía del lugar:</label>
                    <input type="text" name="coverage_classification_scheme_3" class="block w-full mt-2 p-2 border rounded" placeholder="Esquema de clasificación">
                    <input type="text" name="coverage_continent" class="block w-full mt-2 p-2 border rounded" placeholder="Continente">
                    <input type="text" name="coverage_country" class="block w-full mt-2 p-2 border rounded" placeholder="País">
                    <input type="text" name="coverage_region" class="block w-full mt-2 p-2 border rounded" placeholder="Región">
                    <input type="text" name="coverage_state" class="block w-full mt-2 p-2 border rounded" placeholder="Estado">
                    <input type="text" name="coverage_city" class="block w-full mt-2 p-2 border rounded" placeholder="Ciudad">
                    <input type="text" name="coverage_zone" class="block w-full mt-2 p-2 border rounded" placeholder="Zona">
                    <input type="text" name="coverage_address" class="block w-full mt-2 p-2 border rounded" placeholder="Dirección"><br>
                </div>
            </li>
            <li>
                <label for="general_structure">Estructura general<span class="text-red-500">*</span></label><br>
                <select id="general_structure" name="general_structure" class="block w-full mt-2 p-2 border rounded" required>
                    <option value="" disabled selected>Seleccionar</option>
                    <option value="Recopilación">Recopilación</option>
                    <option value="Mixto">Mixto</option>
                    <option value="Lineal">Lineal</option>
                    <option value="Jerárquico">Jerárquico</option>
                    <option value="En red">En red</option>
                    <option value="Bifurcado">Bifurcado</option>
                    <option value="Parcelado">Parcelado</option>
                    <option value="Atómica">Atómica</option>
                </select>
            </li>
            <li>
                <label for="grouping_level">Nivel de agrupación</label><br>
                <select id="grouping_level" name="grouping_level" class="block w-full mt-2 p-2 border rounded">
                    <option value="" disabled selected>Seleccionar</option>
                    <option value="Nivel bajo">Nivel bajo</option>
                    <option value="Nivel medio">Nivel medio</option>
                    <option value="Nivel alto">Nivel alto</option>
                    <option value="Nivel muy alto">Nivel muy alto</option>
                </select>
            </li>
        </ul>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded sendbutton">Guardar y continuar</button><br><br>
    </form>
</div>



<div class="divciclodevida clasesmodal" id="2">
    <form action="{{ route('store', ['type' => 'lifecycle']) }}" method="POST">
        @csrf
        <input type="hidden" name="directory" value="{{ $directory }}">
        <ul>
            <li>
                <label for="life_cycle_version">Versión ciclo de vida<span class="text-red-500">*</span></label>
                <input type="text" id="life_cycle_version" name="life_cycle_version" maxlength="12" required>
            </li>
            <li class="typecheckbox">
                <label for="life_cycle_status">Estado ciclo de vida<span class="text-red-500">*</span></label>
                <input type="text" id="life_cycle_status" name="life_cycle_status" maxlength="40" required>
            </li>
            <li class="typecheckbox">
                <label for="role_life_cycle">Rol ciclo de vida <span class="text-red-500">*</span></label><br>
                <select name="role_life_cycle" id="role_life_cycle" class="block w-full mt-2 p-2 border rounded" required>
                    <option value="" disabled selected>Seleccionar</option>
                    <option value="autor">Autor</option>
                    <option value="editorial">Editorial</option>
                    <option value="desconocido">Desconocido</option>
                    <option value="iniciador">Iniciador</option>
                    <option value="terminador">Terminador</option>
                    <option value="validador">Validador</option>
                    <option value="editor">Editor</option>
                    <option value="disenador_grafico">Diseñador gráfico</option>
                    <option value="implementador_tecnico">Implementador técnico</option>
                    <option value="proveedor_contenido">Proveedor de contenido</option>
                    <option value="validador_tecnico">Validador técnico</option>
                    <option value="validador_educativo">Validador educativo</option>
                    <option value="redactor_secuencias">Redactor de secuencias de comandos</option>
                    <option value="disenador_institucional">Diseñador Institucional</option>
                </select>
            </li>
            <li>
                <label for="life_cycle_entity">Entidad de ciclo de vida<span class="text-red-500">*</span></label><br>
                <input type="text" id="life_cycle_entity" name="life_cycle_entity" maxlength="50" required>
            </li>
            <li>
                <label for="date">Fecha <span class="text-red-500">*</span></label><br>
                <input type="date" id="date" name="date" required>
            </li>
        </ul>   
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded sendbutton">Guardar y continuar</button><br><br>     
    </form>
</div>



<div class="divmetadatos clasesmodal" id="3" style="display: none;">
    <form action="{{ route('store', ['type' => 'metadata']) }}" method="POST">
        @csrf
        <input type="hidden" name="directory" value="{{ $directory }}">
        <ul>
            <li>
                <label for="metadata_identifier">Identificador metadatos<span class="text-red-500">*</span></label>
                <input type="text" id="metadata_identifier" name="metadata_identifier" maxlength="250" required>
            </li>
            <li>
                <label for="meta_metadata_catalogue">Catálogo de Meta-Metadatos</label>
                <input type="text" id="meta_metadata_catalogue" name="meta_metadata_catalogue" maxlength="250">
            </li>
            <li>
                <label for="meta_metadata_entry">Entrada de Meta-Metadatos<span class="text-red-500">*</span></label>
                <input type="text" id="meta_metadata_entry" name="meta_metadata_entry" maxlength="250" required>
            </li>
            <li>
                <label for="role_of_metadata_role">Rol de metadatos</label>
                <button type="button" class="dropdownButton buttondropdown">
                    Mostrar/Ocultar Inputs
                </button>
                <div class="dropdownContent dropdown-content mt-2">
                    <input type="text" class="block w-full mt-2 p-2 border rounded" id="role_of_metadata_role" name="role_of_metadata_role" placeholder="Rol" maxlength="30">
                    <input type="text" class="block w-full mt-2 p-2 border rounded" id="role_of_metadata_contributor" name="role_of_metadata_contributor" placeholder="Contribuyente" maxlength="30">
                    <input type="text" class="block w-full mt-2 p-2 border rounded" id="role_of_metadata_date" name="role_of_metadata_date" placeholder="Fecha" maxlength="30">
                    <input type="email" class="block w-full mt-2 p-2 border rounded" id="role_of_metadata_email" name="role_of_metadata_email" placeholder="e-mail" maxlength="100">
                    <input type="text" class="block w-full mt-2 p-2 border rounded" id="role_of_metadata_institution" name="role_of_metadata_institution" placeholder="Institución" maxlength="30">
                    <input type="text" class="block w-full mt-2 p-2 border rounded" id="role_of_metadata_country" name="role_of_metadata_country" placeholder="País" maxlength="30">
                </div>
            </li>
            <li>
                <label for="meta_metadata_entity">Entidad de Meta-Metadatos</label>
                <input type="text" id="meta_metadata_entity" name="meta_metadata_entity" maxlength="30">
            </li>
            <li>
                <label for="meta_metadata_date">Fecha de Meta-Metadatos</label><br>
                <input type="date" id="meta_metadata_date" name="meta_metadata_date">
            </li>
            <li>
                <label for="meta_metadata_metadata_schema">Esquema de metadatos de Meta-Metadatos</label>
                <input type="text" id="meta_metadata_metadata_schema" name="meta_metadata_metadata_schema" maxlength="30">
            </li>
            <li>
                <label for="metadata_language">Idioma de metadatos</label><br>
                <select id="metadata_language" name="metadata_language" class="block w-full mt-2 p-2 border rounded">
                    <option value="" disabled selected>Seleccionar</option>
                    <option value="eng">Inglés</option>
                    <option value="spa">Español</option>
                    <option value="fra">Francés</option>
                    <option value="deu">Alemán</option>
                    <option value="ita">Italiano</option>
                    <option value="zho">Chino</option>
                    <option value="jpn">Japonés</option>
                    <option value="rus">Ruso</option>
                    <option value="ara">Árabe</option>
                    <option value="por">Portugués</option>
                </select>
            </li>
        </ul>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded sendbutton">Guardar y continuar</button><br><br>
    </form>
</div>




<div class="divcatecnicas clasesmodal" id="4" style="display: none;">
    <form action="{{ route('store', ['type' => 'technical_characteristics']) }}" method="POST">
        @csrf
        <input type="hidden" name="directory" value="{{ $directory }}">
        <ul>
            <li>
                <label for="technical_format">Formato técnico<span class="text-red-500">*</span></label>
                <input type="text" id="technical_format" name="technical_format" maxlength="250" required>
            </li>
            <li>
                <label for="technical_size">Tamaño técnico<span class="text-red-500">*</span></label>
                <input type="text" id="technical_size" name="technical_size" maxlength="50" required>
            </li>
            <li>
                <label for="technical_location">Ubicación técnica<span class="text-red-500">*</span></label>
                <button type="button" class="dropdownButton buttondropdown">
                    Mostrar/Ocultar Inputs
                </button>
                <div class="dropdownContent dropdown-content mt-2">
                    <input type="text" class="block w-full mt-2 p-2 border rounded" id="technical_location_web" name="technical_location_web" placeholder="Web" maxlength="250" required>
                    <input type="text" class="block w-full mt-2 p-2 border rounded" id="technical_location_source" name="technical_location_source" placeholder="Fuente" maxlength="250" required>
                </div>
            </li>
            <li>
                <label for="technical_type">Tipo técnico</label>
                <input type="text" id="technical_type" name="technical_type" maxlength="250">
            </li>
            <li>
                <label for="technical_name">Nombre técnico</label>
                <input type="text" id="technical_name" name="technical_name" maxlength="250">
            </li>
            <li>
                <label for="minimum_technical_version">Versión mínima técnica</label>
                <textarea id="minimum_technical_version" name="minimum_technical_version" rows="8" maxlength="250"></textarea>
            </li>
            <li>
                <label for="maximum_technical_version">Versión máxima técnica</label>
                <textarea id="maximum_technical_version" name="maximum_technical_version" rows="8" maxlength="250"></textarea>
            </li>
            <li>
                <label for="technical_requirements_for_other_platforms">Requisitos técnicos de otras plataformas</label>
                <button type="button" class="dropdownButton buttondropdown">
                    Mostrar/Ocultar Inputs
                </button>
                <div class="dropdownContent dropdown-content mt-2">
                    <input type="text" class="block w-full mt-2 p-2 border rounded" id="technical_requirements_for_other_platforms_type" name="technical_requirements_for_other_platforms_type" placeholder="Tipo" maxlength="50">
                    <input type="text" class="block w-full mt-2 p-2 border rounded" id="technical_requirements_for_other_platforms_instruction" name="technical_requirements_for_other_platforms_instruction" placeholder="Instrucción" maxlength="50">
                    <input type="text" class="block w-full mt-2 p-2 border rounded" id="technical_requirements_for_other_platforms_source" name="technical_requirements_for_other_platforms_source" placeholder="Fuente" maxlength="100">
                    <input type="text" class="block w-full mt-2 p-2 border rounded" id="technical_requirements_for_other_platforms_language" name="technical_requirements_for_other_platforms_language" placeholder="Idioma" maxlength="50">
                </div>
            </li>
            <li>
                <label for="technical_duration">Duración técnica</label>
                <input type="text" id="technical_duration" name="technical_duration" maxlength="50">
            </li>
            <li>
                <label for="use">Uso</label><br>
                <select id="use" name="use" class="block w-full mt-2 p-2 border rounded">
                    <option value="" disabled selected>Seleccionar</option>
                    <option value="movil">Móvil</option>
                    <option value="computador">Computador</option>
                    <option value="movil_computador">Móvil-Computador</option>
                </select>
            </li>
        </ul>
        <button type="submit" class="bg-blue-500 
        text-white px-4 py-2 rounded sendbutton">Guardar y continuar</button><br><br>
    </form>
</div>



<div class="diveducativo clasesmodal" id="5" style="display: none;">
    <form action="{{ route('store', ['type' => 'Educational']) }}" method="POST">
        @csrf
        <input type="hidden" name="directory" value="{{ $directory }}">
        <ul>
            <li>
                <label for="type_of_educational_interactivity">Tipo de interactividad educativa<span class="text-red-500">*</span></label>
                <select id="type_of_educational_interactivity" name="type_of_educational_interactivity" class="block w-full mt-2 p-2 border rounded" required>
                    <option value="" disabled selected>Seleccionar</option>
                    <option value="Alto">Alto</option>
                    <option value="Descriptivo">Descriptivo</option>
                    <option value="Mixto">Mixto</option>
                </select>
            </li>
            <li>
                <label for="type_resources">Tipo de recurso de aprendizaje educativo<span class="text-red-500">*</span></label>
                <textarea name="type_resources" id="type_resources" rows="8" maxlength="250" required></textarea>
            </li>
            <li>
                <label for="level_of_interactivity">Nivel de interactividad<span class="text-red-500">*</span></label>
                <select id="level_of_interactivity" name="level_of_interactivity" class="block w-full mt-2 p-2 border rounded" required>
                    <option value="" disabled selected>Seleccionar</option>
                    <option value="Muy baja">Muy baja</option>
                    <option value="Baja">Baja</option>
                    <option value="Media">Media</option>
                    <option value="Alta">Alta</option>
                    <option value="Muy alta">Muy alta</option>
                </select>
            </li>
            <li>
                <label for="educational_semantic_density">Densidad semántica educativa</label>
                <select id="educational_semantic_density" name="educational_semantic_density" class="block w-full mt-2 p-2 border rounded">
                    <option value="" disabled selected>Seleccionar</option>
                    <option value="Muy baja">Muy baja</option>
                    <option value="Baja">Baja</option>
                    <option value="Media">Media</option>
                    <option value="Alta">Alta</option>
                    <option value="Muy alta">Muy alta</option>
                </select>
            </li>
            <li>
                <label for="educational_user_role">Rol de usuario final educativo deseado</label>
                <input id="educational_user_role" name="educational_user_role" type="text" maxlength="250">
            </li>
            <li>
                <label for="educational_context">Contexto educativo</label>
                <input id="educational_context" name="educational_context" type="text" maxlength="250">
            </li>
            <li>
                <label for="context">Contexto</label><br>
                <select id="context" name="context" class="block w-full mt-2 p-2 border rounded">
                    <option value="" disabled selected>Seleccionar</option>
                    <option value="complementaria">Complementaria</option>
                    <option value="especializacion-tecnologica">Especialización tecnológica</option>
                    <option value="especializacion-tecnica">Especialización técnica</option>
                    <option value="operario-auxiliar">Operario auxiliar</option>
                    <option value="tecnologo">Tecnólogo</option>
                    <option value="tecnico">Técnico</option>
                </select>
            </li>
            <li>
                <label for="educational_age_range">Intervalo de edad típica educativa</label>
                <input id="educational_age_range" name="educational_age_range" type="text" maxlength="250">
            </li>
            <li>
                <label for="educational_difficulty">Dificultad educativa</label>
                <select id="educational_difficulty" name="educational_difficulty" class="block w-full mt-2 p-2 border rounded">
                    <option value="" disabled selected>Seleccionar</option>
                    <option value="Muy baja">Muy baja</option>
                    <option value="Baja">Baja</option>
                    <option value="Media">Media</option>
                    <option value="Alta">Alta</option>
                    <option value="Muy alta">Muy alta</option>
                </select>
            </li>
            <li>
                <label for="learning_time">Tiempo de aprendizaje típico educativo</label>
                <input id="learning_time" name="learning_time" type="text" maxlength="250">
            </li>
            <li>
                <label for="educational_description">Descripción educativa<span class="text-red-500">*</span></label>
                <textarea name="educational_description" id="educational_description" rows="8" maxlength="250" required></textarea>
            </li>
            <li>
                <label for="educational_language">Idioma educativo<span class="text-red-500">*</span></label><br>
                <select id="educational_language" name="educational_language" class="block w-full mt-2 p-2 border rounded" required>
                    <option value="" disabled selected>Seleccionar</option>
                    <option value="eng">Inglés</option>
                    <option value="spa">Español</option>
                    <option value="fra">Francés</option>
                    <option value="deu">Alemán</option>
                    <option value="ita">Italiano</option>
                    <option value="zho">Chino</option>
                    <option value="jpn">Japonés</option>
                    <option value="rus">Ruso</option>
                    <option value="ara">Árabe</option>
                    <option value="por">Portugués</option>
                </select>
            </li>
        </ul>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded sendbutton">Guardar y continuar</button><br><br>
    </form>
</div>


<div class="divderechos clasesmodal" id="6" style="display: none;">
    <form action="{{ route('store', ['type' => 'rights']) }}" method="POST">
        @csrf
        <input type="hidden" name="directory" value="{{ $directory }}">
        <ul>
            <li>
                <label for="">Contribuyentes<span class="text-red-500">*</span></label>
                <button class="dropdownButton buttondropdown">
                    Mostrar/Ocultar Inputs
                </button>
                <div class="dropdownContent dropdown-content mt-2">
                    <input type="text" name="contributors_contributors" id="contributors_contributors" class="block w-full mt-2 p-2 border rounded" placeholder="Contribuyente" maxlength="30" required>
                    <input type="text" name="contributors_role" id="contributors_role" class="block w-full mt-2 p-2 border rounded" placeholder="Rol" maxlength="30" required>
                    <input type="text" name="contributors_date" id="contributors_date" class="block w-full mt-2 p-2 border rounded" placeholder="Fecha" maxlength="30" required>
                    <input type="text" name="contributors_type" id="contributors_type" class="block w-full mt-2 p-2 border rounded" placeholder="Tipo" maxlength="30" required>
                    <input type="text" name="contributors_contact" id="contributors_contact" class="block w-full mt-2 p-2 border rounded" placeholder="Contacto" maxlength="30" required>
                    <input type="text" name="contributors_identifier" id="contributors_identifier" class="block w-full mt-2 p-2 border rounded" placeholder="Identificación" maxlength="30" required>
                    <input type="text" name="contributors_country_of_origin" id="contributors_country_of_origin" class="block w-full mt-2 p-2 border rounded" placeholder="País de origen" maxlength="30" required>
                    <input type="text" name="contributors_institution" id="contributors_institution" class="block w-full mt-2 p-2 border rounded" placeholder="Institución" maxlength="30" required>
                </div>
            </li>
            <li>
                <label for="">Costo de los derechos</label><br>
                <select name="cost_of_fees" id="cost_of_fees" class="block w-full mt-2 p-2 border rounded">
                    <option value="" disabled selected>Seleccionar</option>
                    <option value="Si">Si</option>
                    <option value="No">No</option>
                </select>
            </li>
            <li>
                <label for="">Derechos de copyright y otras restricciones<span class="text-red-500">*</span></label><br>
                <select name="copyright_and_other_restrictions" id="copyright_and_other_restrictions" class="block w-full mt-2 p-2 border rounded">
                    <option value="" disabled selected>Seleccionar</option>
                    <option value="Si">Si</option>
                    <option value="No">No</option>
                </select>
            </li>
            <li>
                <label for="">Descripción de los derechos<span class="text-red-500">*</span></label>
                <textarea name="description_of_rights" id="description_of_rights" rows="8" maxlength="250"></textarea>
            </li>
            <li>
                <label for="">Derecho recurso<span class="text-red-500">*</span></label>
                <select name="right_of_appeal" id="right_of_appeal" class="block w-full mt-2 p-2 border rounded" required>
                    <option value="reconocimiento-no-comercial">Reconocimiento no comercial (by nc)</option>
                    <option value="reconocimiento-compartir-igual">Reconocimiento compartir Igual (by sa)</option>
                    <option value="reconocimiento">Reconocimiento (by)</option>
                    <option value="reconocimiento-sin-obra-derivado">Reconocimiento SinObraDerivado (by nd)</option>
                    <option value="reconocimiento-no-comercial-compartir-igual">Reconocimiento-NoComercial-CompartirIgual (by nc sa)</option>
                    <option value="reconocimiento-no-comercial-sin-obra-derivado">Reconocimiento-NoComercial-SinObraDerivado (by nc nd)</option>
                </select>
            </li>
            <li>
                <label for="">Disponibilidad<span class="text-red-500">*</span></label><br>
                <select name="availability" id="availability" class="block w-full mt-2 p-2 border rounded">
                    <option value="" disabled selected>Seleccionar</option>
                    <option value="Publico">Público</option>
                    <option value="Privado">Privado</option>
                </select>
            </li>
        </ul>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded sendbutton">Guardar y continuar</button><br><br>
    </form>
</div>


<div class="divrelaciones clasesmodal" id="7" style="display: none;">
    <form action="{{ route('store', ['type' => 'relations']) }}" method="POST">
        @csrf 
        <input type="hidden" name="directory" value="{{ $directory }}">
        <ul>
            <li>
                <label for="type_of_relationship">Tipo de relacion</label>
                <input type="text" name="type_of_relationship" id="type_of_relationship" maxlength="250">
            </li>
            <li>
                <label for="relationship_identifier">Identificador de relaciones</label>
                <input type="text" name="relationship_identifier" id="relationship_identifier" maxlength="250">
            </li>
            <li>
                <label for="description_of_relations">Descripcion de relaciones</label>
                <textarea name="description_of_relations" id="description_of_relations" rows="8" maxlength="250"></textarea>
            </li>
            <li>
                <label for="relationship_catalogue">Catalogo de relaciones</label>
                <input type="text" name="relationship_catalogue" id="relationship_catalogue" maxlength="250">
            </li>
            <li>
                <label for="relationship_entry">Entrada de relaciones</label>
                <input type="text" name="relationship_entry" id="relationship_entry" maxlength="250">
            </li>
        </ul>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded sendbutton">Guardar y continuar</button><br><br>
    </form>
</div>


<div class="divcaanotaciones clasesmodal" id="8" style="display: none;">
    <form action="{{ route('store', ['type' => 'annotations']) }}" method="POST">
        @csrf
        <input type="hidden" name="directory" value="{{ $directory }}">
        <ul>
            <li>
                <label for="person_of_annotation">Persona de anotación</label>
                <input type="text" id="person_of_annotation" name="person_of_annotation" maxlength="250">
            </li>
            <li>
                <label for="date_of_annotation">Fecha de anotación</label><br>
                <input type="date" id="date_of_annotation" name="date_of_annotation">
            </li>
            <li>
                <label for="description">Descripción</label>
                <textarea id="description" name="description" rows="8" maxlength="250"></textarea>
            </li>
        </ul>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded sendbutton">Guardar y continuar</button><br><br>
    </form>
</div>



<div class="divclasificacion clasesmodal" id="9" style="display: none;">
    <form action="{{ route('store', ['type' => 'classification']) }}" method="POST">
        @csrf 
        <input type="hidden" name="directory" value="{{ $directory }}">
        <ul>
            <li>
                <label for="purpose_of_classification">Proposito de clasificación</label>
                <input type="text" id="purpose_of_classification" name="purpose_of_classification" maxlength="250">
            </li>
            <li>
                <label for="origin_of_the_classification">Origen de la clasificación<span class="text-red-500">*</span></label>
                <input type="text" id="origin_of_the_classification" name="origin_of_the_classification" maxlength="250" required>
            </li>
            <li>
                <label for="classification_taxon">Taxon de clasificación</label>
                <input type="text" id="classification_taxon" name="classification_taxon" maxlength="250">
            </li>
            <li>
                <label for="classification_id">ID de clasificación</label>
                <input type="text" id="classification_id" name="classification_id" maxlength="250">
            </li>
            <li>
                <label for="classification_entry">Entrada de clasificación</label>
                <input type="text" id="classification_entry" name="classification_entry" maxlength="250">
            </li>
            <li>
                <label for="description_of_the_classification">Descripción de la clasificación</label>
                <textarea id="description_of_the_classification" name="description_of_the_classification" rows="8" maxlength="250"></textarea>
            </li>
            <li>
                <label for="classification_keywords">Palabras clave de clasificación</label>
                <input type="text" id="classification_keywords" name="classification_keywords" maxlength="250">
            </li>
            <li>
                <label for="name_of_the_programme">Nombre del programa<span class="text-red-500">*</span></label>
                <input type="text" id="name_of_the_programme" name="name_of_the_programme" maxlength="250" required>
            </li>
            <li>
                <label for="programme_code">Código del programa<span class="text-red-500">*</span></label>
                <input type="text" id="programme_code" name="programme_code" maxlength="250" required>
            </li>
            <li>
                <label for="knowledge_network">Red de conocimiento<span class="text-red-500">*</span></label>
                <input type="text" id="knowledge_network" name="knowledge_network" maxlength="250" required>
            </li>
            <li>
                <label for="occupational_area">Área ocupacional<span class="text-red-500">*</span></label>
                <input type="text" id="occupational_area" name="occupational_area" maxlength="250" required>
            </li>
            <li>
            <label for="training_center">Centro de formacion/empresa<span class="text-red-500">*</span></label>
                <textarea id="training_center" name="training_center" rows="8" maxlength="250"></textarea>
            </li>
        </ul>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded sendbutton">Guardar y continuar</button><br><br>
    </form>
</div>

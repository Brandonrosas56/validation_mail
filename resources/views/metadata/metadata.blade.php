<x-app-layout>
    <div class="flex flex-col sm:flex-row mt-8 group1">
        
        <!-- Primera seccion despliegue de archivos-->
        <div class="flex-auto p-4 justify-center items-center div1">
            <h1 class="titulo1">Control</h1>
            <img src="{{ asset('img/metadata/Archivo.png') }}" alt="Error" class="imgArchivo">
           
        </div>

        <!--Selectores de los modales -->
        <div class="flex-auto p-4 div2">
          
            <ul>
                <li><a onclick="mostrarFormulario(1)" href="#"><img src="{{ asset('img/metadata/General.png') }}" alt="Error" class="imgGeneral"></a></li>
                <li><a onclick="mostrarFormulario(2)" href="#"><img src="{{ asset('img/metadata/ciclo de vida.png') }}" alt="Error"></a></li>
                <li><a onclick="mostrarFormulario(3)" href="#"><img src="{{ asset('img/metadata/meta metadatos.png') }}" alt="Error"></a></li>
                <li><a onclick="mostrarFormulario(4)" href="#"><img src="{{ asset('img/metadata/Caracteristicas tecnicas.png') }}" alt="Error"></a></li>
                <li><a onclick="mostrarFormulario(5)" href="#"><img src="{{ asset('img/metadata/Educativo.png') }}" alt="Error"></a></li>
                <li><a onclick="mostrarFormulario(6)" href="#"><img src="{{ asset('img/metadata/Derechos.png') }}" alt="Error"></a></li>
                <li><a onclick="mostrarFormulario(7)" href="#"><img src="{{ asset('img/metadata/Relaciones.png') }}" alt="Error"></a></li>
                <li><a onclick="mostrarFormulario(8)" href="#"><img src="{{ asset('img/metadata/Anotaciones.png') }}" alt="Error"></a></li>
                <li><a onclick="mostrarFormulario(9)" href="#"><img src="{{ asset('img/metadata/Clasificacion.png') }}" alt="Error"></a></li>
            </ul>
        </div>

        <!--Modales-->
        <div class="flex-auto p-4 div3">
            @include('components.modal-metadata')
        </div>
    </div>


    <script>
        function mostrarFormulario(id) {

        document.querySelectorAll('[class^="div"]').forEach((modal) => {
            modal.style.display = 'none';
        });
        
        document.getElementById(id).style.display = 'block';
    }



        document.querySelectorAll('.dropdownButton').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault(); // Evitar el comportamiento predeterminado del botón
            const content = this.nextElementSibling;
            content.classList.toggle('show');
        });
    });

    
    </script>
</x-app-layout>


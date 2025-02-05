<!-- audit.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Auditoría') }}
        </h2>
    </x-slot>

    <!-- Search register -->
    <form method="GET" action="{{ route('audit.search') }}">
        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($errors->any())
            <div class="mb-4">
                <ul class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
            <!-- Creation Date -->
            <div class="flex items-center mb-4 ">
                <label for="creationDateFrom" class="text-gray-700 " >{{ __('Creation date from') }}</label>
                <input type="date" id="creationDateFrom" name="creationDateFrom"
                    class="ml-4 appearance-none w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg py-2 px-3 leading-tight focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 ">
                <br>
               
                <label for="creationDateTo" class="ml-4 text-gray-700">{{ __('Creation date to') }}</label>
                <input type="date" id="creationDateTo" name="creationDateTo"
                    class="ml-4 block appearance-none w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg py-2 px-3 leading-tight focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>
    
            <!-- Update Date -->
            <div class="flex items-center space-x-4 mb-4">
                <label for="updateStartDate" class="text-gray-700">{{ __('Update date from') }}</label>
                <input type="date" id="updateStartDate" name="updateStartDate"
                    class="ml-4   appearance-none w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg py-2 px-3 leading-tight focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <label for="updateDateTo" class=" ml-4 text-gray-700">{{ __('Update date to') }}</label>
                <input type="date" id="updateDateTo" name="updateDateTo"
                    class=" ml-4  block appearance-none w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg py-2 px-3 leading-tight focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>
    
            <!--  Data y Button -->
            <div class="flex items-center space-x-4 mb-4">
                <label for="dataType" class="text-gray-700">{{ __('Data') }}</label>
                <select id="dataType" name="dataType"
                    class="ml-4 block appearance-none w-48 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg py-2 px-3 leading-tight focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option disabled selected>Seleccionar</option>
                    <option value="id">id</option>
                    <option value="author">Autor</option>
                    <option value="event">Evento</option>
                    <option value="previous_state">Estado Previo</option>
                    <option value="new_state">Nuevo Estado</option>
                </select>
                <input name="search" type="search" id="search"
                    class="ml-4  block appearance-none w-48 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg py-2 px-3 leading-tight focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="{{ __('Search') }}" aria-label="Buscar" id="exampleFormControlInput2"
                    aria-describedby="button-addon2" />
                <button id="buscar"
                    class="ml-4 text-white bg-gray-800 hover:bg-gray-900 font-bold py-2 px-4 rounded">{{ __('Search') }}</button>
            </div>
        </div>
    </form>
    <!-- table audit -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="overflow-x-auto">
                    <table class="w-full rounded-lg overflow-hidden">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="py-2 px-4">id</th>
                                <th class="py-2 px-4">Email</th>
                                <th class="py-2 px-4">Autor</th>
                                <th class="py-2 px-4">Evento</th>
                                <th class="py-2 px-4">Estado Previo</th>
                                <th class="py-2 px-4">Nuevo Estado</th>
                                <th class="py-2 px-4">Fecha Creación</th>
                                <th class="py-2 px-4">Fecha Actualización</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($audits as $audit)
                                <tr>
                                    <td class="py-2 px-4 border">{{ $audit->id }}</td>
                                    <td class="py-2 px-4 border">{{ $audit->user->email }}</td>
                                    <td class="py-2 px-4 border">{{ $audit->author }}</td>
                                    <td class="py-2 px-4 border">{{ $audit->event }}</td>
                                    <td class="py-2 px-4 border">{{ $audit->previous_state }}</td>
                                    <td class="py-2 px-4 border">{{ $audit->new_state }}</td>
                                    <td class="py-2 px-4 border">{{ $audit->created_at }}</td>
                                    <td class="py-2 px-4 border">{{ $audit->updated_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{$audits->links()}}
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
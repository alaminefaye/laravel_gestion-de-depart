<!-- New Departure Modal -->
<div id="newDepartureModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-3/4 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Add New Departure</h3>
            <form action="{{ route('departures.store') }}" method="POST" class="mt-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label for="route" class="block text-sm font-medium text-gray-700">Route</label>
                        <input type="text" name="route" id="route" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="mb-4">
                        <label for="bus_id" class="block text-sm font-medium text-gray-700">Bus</label>
                        <select name="bus_id" id="bus_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach($buses as $bus)
                                <option value="{{ $bus->id }}">{{ $bus->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="scheduled_date" class="block text-sm font-medium text-gray-700">Date</label>
                        <input type="date" name="scheduled_date" id="scheduled_date" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="mb-4">
                        <label for="scheduled_time" class="block text-sm font-medium text-gray-700">Time</label>
                        <input type="time" name="scheduled_time" id="scheduled_time" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="mb-4">
                        <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                        <input type="number" name="price" id="price" step="0.01" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="scheduled">Scheduled</option>
                            <option value="departed">Departed</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="delayed">Delayed</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="occupancy_rate" class="block text-sm font-medium text-gray-700">Occupancy Rate (%)</label>
                        <input type="number" name="occupancy_rate" id="occupancy_rate" min="0" max="100" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <div class="mt-4 flex justify-end space-x-3">
                    <button type="button" onclick="closeNewDepartureModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        Add Departure
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

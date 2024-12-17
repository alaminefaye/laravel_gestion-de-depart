<!-- Update Status Modal -->
<div id="updateStatusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Update Departure Status</h3>
            <form id="updateStatusForm" method="POST" class="mt-4">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="new_status" class="block text-sm font-medium text-gray-700">New Status</label>
                    <select name="status" id="new_status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="toggleNewTimeField()">
                        <option value="scheduled">Scheduled</option>
                        <option value="departed">Departed</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="delayed">Delayed</option>
                    </select>
                </div>

                <div id="newTimeField" class="mb-4 hidden">
                    <label for="new_time" class="block text-sm font-medium text-gray-700">New Time</label>
                    <input type="time" name="new_time" id="new_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div class="mt-4 flex justify-end space-x-3">
                    <button type="button" onclick="closeUpdateStatusModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

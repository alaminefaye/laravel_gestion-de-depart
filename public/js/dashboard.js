// Modal handling functions
function openNewDepartureModal() {
    document.getElementById('newDepartureModal').classList.remove('hidden');
}

function closeNewDepartureModal() {
    document.getElementById('newDepartureModal').classList.add('hidden');
}

function openUpdateStatusModal(departureId) {
    const modal = document.getElementById('updateStatusModal');
    const form = document.getElementById('updateStatusForm');
    form.action = `/departures/${departureId}/update-status`;
    modal.classList.remove('hidden');
}

function closeUpdateStatusModal() {
    document.getElementById('updateStatusModal').classList.add('hidden');
}

function toggleNewTimeField() {
    const status = document.getElementById('new_status').value;
    const newTimeField = document.getElementById('newTimeField');
    if (status === 'delayed') {
        newTimeField.classList.remove('hidden');
    } else {
        newTimeField.classList.add('hidden');
    }
}

// Confirmation for deletion
function confirmDelete(departureId) {
    if (confirm('Are you sure you want to delete this departure?')) {
        document.getElementById(`deleteForm${departureId}`).submit();
    }
}

// Close modals when clicking outside
window.onclick = function(event) {
    const newDepartureModal = document.getElementById('newDepartureModal');
    const updateStatusModal = document.getElementById('updateStatusModal');
    
    if (event.target === newDepartureModal) {
        closeNewDepartureModal();
    }
    if (event.target === updateStatusModal) {
        closeUpdateStatusModal();
    }
}

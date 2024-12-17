// Bus Management JavaScript

function openNewBusModal() {
    document.getElementById('newBusModal').classList.remove('hidden');
}

function closeNewBusModal() {
    document.getElementById('newBusModal').classList.add('hidden');
    document.getElementById('newBusForm').reset();
}

function openUpdateBusModal(busId) {
    document.getElementById('updateBusModal').classList.remove('hidden');
    document.getElementById('updateBusForm').action = `/dashboard/buses/${busId}/update-status`;
}

function closeUpdateBusModal() {
    document.getElementById('updateBusModal').classList.add('hidden');
}

// Handle form submissions
document.addEventListener('DOMContentLoaded', function() {
    // New Bus Form
    const newBusForm = document.getElementById('newBusForm');
    if (newBusForm) {
        newBusForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            try {
                const response = await fetch('/dashboard/buses/store', {
                    method: 'POST',
                    body: new FormData(this),
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (response.ok) {
                    window.location.reload();
                } else {
                    console.error('Error adding bus');
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });
    }

    // Update Bus Status Form
    const updateBusForm = document.getElementById('updateBusForm');
    if (updateBusForm) {
        updateBusForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: new FormData(this),
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (response.ok) {
                    window.location.reload();
                } else {
                    console.error('Error updating bus status');
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });
    }
});

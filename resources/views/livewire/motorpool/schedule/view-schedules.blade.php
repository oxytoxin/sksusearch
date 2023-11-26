<div class="p-10">
    <div class="bg-primary-200 p-4 rounded-md">
        <div class="grid grid-cols-4 mb-4">
            <x-native-select class="col-span-1" id="vehicle-select" label="Vehicle" wire:model="vehicle">
                <option value="" selected>All</option>
                @forelse ($vehicles as $vehicle)
                    <option class="uppercase" value="{{ $vehicle->id }}">{{ $vehicle->campus->name }} - {{ $vehicle->model }}</option>
                @empty
                    <option>No vehicle</option>
                @endforelse
            </x-native-select>
            {{-- @dump($events) --}}
        </div>
        <div wire:ignore>
            <div id="calendar"></div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal" id="myModal">
        <div class="modal-content">
            <div class="flex justify-between items-center">
                <h2 class="text-center" id="modalTitle"></h2>
                <span class="close">&times;</span>

            </div>
            <p id="modalBody"></p>
        </div>
    </div>
    @push('scripts')
        <style>
            .modal {
                display: none;
                position: fixed;
                z-index: 1;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
                background-color: rgba(0, 0, 0, 0.4);
                transition: opacity 0.5s;
            }

            .modal.fade {
                opacity: 0;
                pointer-events: none;
            }

            .modal-content {
                background-color: #fefefe;
                margin: 10% auto;
                padding: 20px;
                border: 1px solid #888;
                width: 80%;
                border-radius: 8px;
                line-height: 1.5;
            }

            .modal-content p {
                margin-bottom: 5px;
                margin-top: 5px;
            }

            .fc-event{
                cursor: pointer;
                text-align: center;
                margin-bottom: 1rem;
            }

            #calendar .fc-button {
                background-color: #0a5200;
                border-color: #0a5200;
            }

            .close {
                color: #aaa;
                float: right;
                font-size: 28px;
                font-weight: bold;
            }

            .close:hover,
            .close:focus {
                color: black;
                text-decoration: none;
                cursor: pointer;
            }
        </style>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    initialDate: '{{ $year }}-{{ $month }}-01',
                    views: {
                        timeGridWeek: {
                            type: 'timeGrid',
                            duration: {
                                days: 7
                            },
                            buttonText: 'week'
                        }
                    },
                    headerToolbar: {
                        start: 'prev next',
                        center: 'title',
                        end: 'today timeGridWeek dayGridMonth'
                    },
                    displayEventTime: false,
                    eventColor: '#0a5200',
                    eventDisplay: 'block',
                    events: {!! json_encode($events) !!},
                    eventClick: function(info) {
                        // Display additional information in a modal-like dialog
                        var modal = document.getElementById('myModal');
                        var modalTitle = document.getElementById('modalTitle');
                        var modalBody = document.getElementById('modalBody');
                        const options = { month: 'long', day: 'numeric', year: 'numeric' };
                        const formattedDateFrom = info.event.start.toLocaleString('en-US', options);
                        const formattedDateTo = info.event.end.toLocaleString('en-US', options);
                        modalTitle.innerHTML = '<span class="font-bold">' + info.event.title + '</span>';
                        modalBody.innerHTML = '<div class="bg-primary-100 mt-3 p-3 rounded-md"><p>Date of Travel: ' + formattedDateFrom + ' - ' + formattedDateTo +
                            '</p><p>Time: ' + info.event.start.toLocaleTimeString() + ' - ' + info.event.end
                            .toLocaleTimeString() + '</p><p>Purpose: ' + info.event.extendedProps.purpose +
                                 '</p><p>Vehicle: ' + info.event.extendedProps.campus + ' - '+ info.event.extendedProps.vehicle + ' (' + info.event.extendedProps.plate_number + ') </p>'
                                 + '</p><p>Driver: ' + info.event.extendedProps.driver + '</p>';
                        modal.style.display = 'block';
                        var closeButton = document.getElementsByClassName('close')[0];
                        closeButton.onclick = function() {
                            modal.style.display = 'none';
                        }
                        document.addEventListener('keydown', function(event) {
                        if (event.key === 'Escape') {
                            modal.style.display = 'none';
                        }
                        });
                    }
                });
                calendar.render();
                window.addEventListener('refreshCalendar', event => {
                    console.log(event.detail);
                    calendar.batchRendering(() => {
                        calendar.getEvents().forEach(event => event.remove());
                        calendar.addEventSource(event.detail.events);
                    });
                });
            });
        </script>
    @endpush
</div>

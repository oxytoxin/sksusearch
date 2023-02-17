<div class="p-10">
    <div>
        <div id="calendar"></div>
      </div>
      <!-- Modal -->
      <div id="myModal" class="modal">
        <div class="modal-content">
          <span class="close">&times;</span>
          <h2 id="modalTitle"></h2>
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
        }
        .modal-content {
          background-color: #fefefe;
          margin: 10% auto;
          padding: 20px;
          border: 1px solid #888;
          width: 80%;
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
        views: {
          timeGridWeek: {
            type: 'timeGrid',
            duration: { days: 7 },
            buttonText: 'week'
          }
        },
        headerToolbar: {
          start: 'prev,next',
          center: 'title',
          end: 'timeGridWeek today dayGridMonth'
        },
        events: {!! $events !!},
        eventClick: function(info) {
           // Display additional information in a modal-like dialog
           var modal = document.getElementById('myModal');
          var modalTitle = document.getElementById('modalTitle');
          var modalBody = document.getElementById('modalBody');
          modalTitle.innerHTML = info.event.title;
          modalBody.innerHTML = '<p>Date: ' + info.event.start.toLocaleDateString() + '</p><p>Time: ' + info.event.start.toLocaleTimeString() + ' - ' + info.event.end.toLocaleTimeString() + '</p><p>Purpose: ' + info.event.extendedProps.purpose + '</p>';
          modal.style.display = 'block';

          var closeButton = document.getElementsByClassName('close')[0];
          closeButton.onclick = function() {
            modal.style.display = 'none';
          }
        }
      });
      calendar.render();
    });
  </script>
@endpush


</div>

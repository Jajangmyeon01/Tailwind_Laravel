<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Vite Link for Tailwind CSS -->
  @vite('resources/css/app.css')
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script src="https://kit.fontawesome.com/44d3cca51b.js" crossorigin="anonymous"></script>
  <title>Laravel CRUD with Tailwind CSS</title>
</head>

<body class="bg-gray-50 min-h-screen flex flex-col items-center py-10">

  <!-- Modal -->
  <div id="studentmodal" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-60 z-50">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-8">
      <div class="border-b pb-4 mb-6">
        <h5 class="text-2xl font-bold text-gray-900">Add New Student</h5>
      </div>

      <div>
        <form id="submitForm">
          <div class="mb-5">
            <label for="fname" class="block text-gray-700 font-medium">First Name:</label>
            <input type="text" name="fname" class="mt-2 block w-full border border-gray-300 rounded-lg p-3 focus:ring focus:ring-indigo-200" id="fname" placeholder="Enter first name">
          </div>

          <input type="hidden" name="id" id="id">

          <div class="mb-5">
            <label for="lname" class="block text-gray-700 font-medium">Last Name:</label>
            <input type="text" name="lname" class="mt-2 block w-full border border-gray-300 rounded-lg p-3 focus:ring focus:ring-indigo-200" id="lname" placeholder="Enter last name">
          </div>

          <div class="mb-5">
            <label for="username" class="block text-gray-700 font-medium">Username:</label>
            <input type="text" name='username' class="mt-2 block w-full border border-gray-300 rounded-lg p-3 focus:ring focus:ring-indigo-200" id="username" placeholder="Enter username">
          </div>

          <div class="mb-5">
            <label for="course" class="block text-gray-700 font-medium">Course:</label>
            <select class="block w-full border border-gray-300 rounded-lg p-3 focus:ring focus:ring-indigo-200" name="course" id="course">
              <option value="" disabled selected>Choose your course</option>
              <option value="BSIT">Bachelor of Science in Information Technology (BSIT)</option>
              <option value="BSIS">Bachelor of Science in Information System (BSIS)</option>
              <option value="ACT">Associate in Computer Technology (ACT)</option>
              <option value="BSCS">Bachelor of Science in Computer Science (BSCS)</option>
            </select>
          </div>

          <div class="mb-5">
            <label for="email" class="block text-gray-700 font-medium">Email:</label>
            <input type="email" name="email" class="mt-2 block w-full border border-gray-300 rounded-lg p-3 focus:ring focus:ring-indigo-200" id="email" placeholder="Enter email">
          </div>

          <div class="flex justify-end">
            <button type="button" class="bg-gray-500 text-white rounded-lg px-4 py-2 mr-2 shadow-md hover:bg-gray-600 transition duration-300 ease-in-out" data-dismiss="modal" id="close-modal">Close</button>
            <button type="submit" class="bg-indigo-600 text-white rounded-lg px-4 py-2 shadow-md hover:bg-indigo-700 transition duration-300 ease-in-out">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <h1 class="text-4xl font-extrabold text-gray-900 mb-10">Laravel with Tailwind</h1>

  <div class="flex justify-between w-full max-w-4xl mb-8">
    <button type="button" id="modal-btn" class="bg-indigo-600 text-white rounded-lg px-6 py-3 flex items-center gap-2 shadow-md hover:bg-indigo-700 transition duration-300 ease-in-out">
      <i class="fa-solid fa-plus"></i> Add student
    </button>
    <button type="button" id="delete-all-btn" class="bg-red-600 text-white rounded-lg px-6 py-3 flex items-center gap-2 shadow-md hover:bg-red-700 transition duration-300 ease-in-out">
      <i class="fa-solid fa-trash"></i> Delete All
    </button>
  </div>

  <div class="w-full max-w-4xl bg-white rounded-2xl shadow-lg overflow-hidden">
    <table class="min-w-full bg-white border border-gray-200 rounded-lg">
      <thead class="bg-gray-100 text-gray-700">
        <tr>
          <th class="p-4 text-left">Student #</th>
          <th class="p-4 text-left">First Name</th>
          <th class="p-4 text-left">Last Name</th>
          <th class="p-4 text-left">Username</th>
          <th class="p-4 text-left">Course</th>
          <th class="p-4 text-left">Email</th>
          <th class="p-4 text-center">Actions</th>
        </tr>
      </thead>
      <tbody id="student-data" data-status='true' class="text-gray-700">
      </tbody>
      <tr class="default-row">
        <td colspan="7" id="respanel" class="bg-gray-50 p-6 text-center text-gray-500">No data available...</td>
      </tr>
    </table>
  </div>

  <script>
    $(document).ready(function() {
      $('#modal-btn').on('click', function() {
        $('#submitForm')[0].reset();
        $('#id').val('');
        $('#studentmodal').removeClass('hidden').addClass('flex');
      });

      $('#close-modal').on('click', function() {
        $('#studentmodal').removeClass('flex').addClass('hidden');
      });

      $('#submitForm').on('submit', function(e) {
        e.preventDefault();
        const data = $(this).serialize();

        $.ajax({
          url: "adddata",
          type: "POST",
          data: {
            "_token": "{{csrf_token()}}",
            data: data
          },
          success: function(res) {
            $('#respanel').html(res);
            $('#submitForm')[0].reset();
            $('#studentmodal').removeClass('flex').addClass('hidden');
            fetchrecords();
          }
        });
      });

      $(document).on('click', '.bg-primary', function(e) {
        e.preventDefault();
        const id = $(this).val();
        $.ajax({
          url: 'editdata',
          type: 'POST',
          data: {
            "_token": "{{csrf_token()}}",
            id: id
          },
          success: function(res) {
            $('#submitForm')[0].reset();
            $('#id').val(res.id);
            $('#fname').val(res.fname);
            $('#lname').val(res.lname);
            $('#username').val(res.username);
            $('#course').val(res.course);
            $('#email').val(res.email);
            $('#studentmodal').removeClass('hidden').addClass('flex');
          }
        });
      });

      $(document).on('click', '.bg-danger', function(e) {
        e.preventDefault();
        const id = $(this).val();
        $.ajax({
          url: 'deletedata',
          type: 'POST',
          data: {
            "_token": "{{csrf_token()}}",
            id: id
          },
          success: function(res) {
            $('#respanel').html(res);
            fetchrecords();
          }
        });
      });

      function fetchrecords() {
        $.ajax({
          url: 'getdata',
          type: 'GET',
          success: function(res) {
            let html = "";
            res.forEach(data => {
              html += `
              <tr class="border-b hover:bg-gray-50 transition duration-300 ease-in-out">
                <th scope="row" class="p-4">${data.id}</th>
                <td class="p-4">${data.fname}</td>
                <td class="p-4">${data.lname}</td>
                <td class="p-4">${data.username}</td>
                <td class="p-4">${data.course}</td>
                <td class="p-4">${data.email}</td>
                <td class="flex justify-center gap-2 p-4">
                  <button class="bg-blue-500 text-white rounded-lg p-2 shadow-md hover:bg-blue-600 transition duration-300 ease-in-out" value="${data.id}"><span><i class="fa-solid fa-pencil"></i></span> Edit</button>
                  <button class="bg-red-500 text-white rounded-lg p-2 shadow-md hover:bg-red-600 transition duration-300 ease-in-out" value="${data.id}"><span><i class="fa-solid fa-trash"></i></span> Delete</button>
                </td> 
              </tr>`;
            });
            $('#student-data').html(html);
          }
        });
      }

      fetchrecords();
    });
  </script>
</body>
</html>

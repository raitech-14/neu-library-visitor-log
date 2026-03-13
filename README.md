<h1>NEU Library Visitor Log System</h1>

<p>
  The <strong>NEU Library Visitor Log System</strong> is a web-based system developed to record and monitor library visitors digitally.
  It allows visitors to check in using their institutional email, full name, college, and purpose of visit.
  The system automatically records the date and time of entry and provides an admin dashboard for monitoring visitor activity.
</p>

<hr>

<h2>Project Description</h2>
<p>
  This system was created to replace manual library logbooks with a digital visitor recording system.
  It helps library staff track visitors more efficiently and accurately.
  The admin can monitor visitor counts per day, week, month, and per college, search visitor records, and block or unblock users when needed.
</p>

<hr>

<h2>Objectives</h2>
<ul>
  <li>To record library visitors digitally</li>
  <li>To monitor visitor activity in the library</li>
  <li>To track visitors per day, week, month, and per college</li>
  <li>To provide an admin dashboard for managing visitor records</li>
  <li>To allow blocking and unblocking of visitors</li>
</ul>

<hr>

<h2>System Features</h2>
<ul>
  <li>Visitor check-in using institutional email</li>
  <li>Records full name, college, and purpose of visit</li>
  <li>Automatically saves visit date and time</li>
  <li>Admin login system</li>
  <li>Dashboard with visitor statistics</li>
  <li>Visitors per day, week, month, and per college</li>
  <li>Search visitor records by name, email, and college</li>
  <li>Filter visitor logs by date</li>
  <li>Block and unblock visitors</li>
  <li>Warning card for blocked visitors</li>
  <li>Chart for visitors per college</li>
</ul>

<hr>

<h2>User Roles</h2>

<h3>Visitor</h3>
<p>The visitor can:</p>
<ul>
  <li>Enter full name</li>
  <li>Enter institutional email</li>
  <li>Enter college or department</li>
  <li>Select purpose of visit</li>
  <li>Check in to the library</li>
</ul>

<h3>Admin</h3>
<p>The admin can:</p>
<ul>
  <li>Log in to the dashboard</li>
  <li>View visitor statistics</li>
  <li>View visitor logs</li>
  <li>Search and filter records</li>
  <li>Block or unblock visitors</li>
  <li>Monitor visitors per college</li>
</ul>

<hr>

<h2>Technologies Used</h2>
<ul>
  <li><strong>PHP</strong> - server-side scripting language</li>
  <li><strong>MySQL</strong> - database management system</li>
  <li><strong>Bootstrap</strong> - front-end framework for design</li>
  <li><strong>Chart.js</strong> - chart library for data visualization</li>
  <li><strong>XAMPP</strong> - local development server</li>
  <li><strong>GitHub</strong> - repository and project documentation</li>
</ul>

<hr>

<h2>Database Structure</h2>

<h3>users Table</h3>
<table border="1" cellpadding="8" cellspacing="0">
  <tr>
    <th>Field</th>
    <th>Description</th>
  </tr>
  <tr>
    <td>id</td>
    <td>Unique user ID</td>
  </tr>
  <tr>
    <td>email</td>
    <td>Institutional email address</td>
  </tr>
  <tr>
    <td>full_name</td>
    <td>Name of the visitor</td>
  </tr>
  <tr>
    <td>college</td>
    <td>College or department of the visitor</td>
  </tr>
  <tr>
    <td>role</td>
    <td>User role (visitor or admin)</td>
  </tr>
  <tr>
    <td>password</td>
    <td>Password for admin login</td>
  </tr>
  <tr>
    <td>is_blocked</td>
    <td>Status if the visitor is blocked or not</td>
  </tr>
</table>

<h3>visit_logs Table</h3>
<table border="1" cellpadding="8" cellspacing="0">
  <tr>
    <th>Field</th>
    <th>Description</th>
  </tr>
  <tr>
    <td>id</td>
    <td>Unique log ID</td>
  </tr>
  <tr>
    <td>user_id</td>
    <td>Reference to the user ID</td>
  </tr>
  <tr>
    <td>purpose</td>
    <td>Purpose of visit</td>
  </tr>
  <tr>
    <td>visit_date</td>
    <td>Date of visit</td>
  </tr>
  <tr>
    <td>visit_time</td>
    <td>Time of visit</td>
  </tr>
</table>

<hr>

<h2>How the System Works</h2>
<ol>
  <li>The visitor enters full name, institutional email, college, and purpose of visit.</li>
  <li>The system checks if the email is valid and ends with <strong>@neu.edu.ph</strong>.</li>
  <li>If the visitor is new, the system saves the visitor information in the database.</li>
  <li>The system records the visit date and time in the visitor logs.</li>
  <li>If the visitor is blocked by admin, the system shows a blocked warning card.</li>
  <li>The admin can log in to the dashboard to monitor all visitor activity.</li>
</ol>

<hr>

<h2>Automatic Visitor Registration</h2>

<p>
The system automatically registers students when they log in using a valid institutional email.
If the email address ends with <strong>@neu.edu.ph</strong>, the system considers it a valid NEU email and allows the visitor to check in.
</p>

<p>
When a student enters their information for the first time, the system will:
</p>

<ul>
  <li>Check if the email ends with <strong>@neu.edu.ph</strong></li>
  <li>If the email is valid, the system automatically creates a visitor record</li>
  <li>The visitor information (name, email, and college) is saved in the <strong>users</strong> table</li>
  <li>The visit is recorded in the <strong>visit_logs</strong> table with date and time</li>
</ul>

<p>
This means students do not need to create an account beforehand. 
Any student using a valid NEU institutional email can be automatically registered and logged into the library visitor system.
</p>

<p>
If the visitor is marked as <strong>blocked</strong> by the administrator, the system will prevent entry and display a red warning card informing the visitor that they are not allowed to enter the library.
</p>
<hr>

<h2>Admin Login Credentials</h2>
<p>Use the following sample admin account:</p>
<ul>
  <li><strong>Email:</strong> admin@neu.edu.ph</li>
  <li><strong>Password:</strong> admin123</li>
</ul>

<hr>

<h2>Live Website</h2>
<p>
The system can be accessed online through the following link:
</p>

<a href="http://neulibraryvisitor.infinityfree.me" target="_blank">
Open NEU Library Visitor Log System
</a>

<hr> 

<h2>Future Improvements</h2>
<ul>
  <li>Add RFID support</li>
  <li>Add printable reports</li>
  <li>Add downloadable visitor logs</li>
  <li>Add more detailed analytics and charts</li>
  <li>Add email notifications for blocked users</li>
</ul>

<hr>

<h2>Developer</h2>
<p>
  <strong>Raiza M. Visaya</strong><br>
  2BSIT5 
</p>

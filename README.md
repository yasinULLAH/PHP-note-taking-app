```markdown
# PHP Note-Taking App

A simple PHP-based web application for managing and tracking personal notes. This application includes user authentication, allowing users to securely create, view, edit, and delete their notes. Data is stored locally using CSV files, making it easy to set up and use without the need for a database server.

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
  - [Login](#login)
  - [Add a New Note](#add-a-new-note)
  - [View Notes](#view-notes)
  - [Edit a Note](#edit-a-note)
  - [Delete a Note](#delete-a-note)
  - [Logout](#logout)
- [Dependencies](#dependencies)
- [File Structure](#file-structure)
- [Data Persistence](#data-persistence)
- [Input Validation](#input-validation)
- [Extensibility](#extensibility)
- [Security Considerations](#security-considerations)
- [License](#license)

## Features

- **User Authentication**: Secure login system with predefined credentials.
- **Create Notes**: Add new notes with a title and content.
- **View Notes**: Display a list of all your notes.
- **Edit Notes**: Modify existing notes.
- **Delete Notes**: Remove unwanted notes.
- **Data Persistence**: Stores user and note data in CSV files (`users.csv` and `notes.csv`).
- **Responsive UI**: Clean and user-friendly interface built with HTML and CSS.

## Installation

### Prerequisites

- **PHP**: Ensure PHP is installed on your system. You can download it from [php.net](https://www.php.net/downloads.php).
- **Web Server**: Use a local web server like [XAMPP](https://www.apachefriends.org/index.html), [WAMP](http://www.wampserver.com/), or [MAMP](https://www.mamp.info/en/) to run the application.

### Steps

1. **Clone the Repository**

   Open your terminal or command prompt and execute:

   ```bash
   git clone https://github.com/yasinULLAH/PHP-note-taking-app.git
   cd PHP-note-taking-app
   ```

2. **Set Up the Web Server**

   - **Using XAMPP (Example):**
     - Place the cloned repository folder (`PHP-note-taking-app`) inside the `htdocs` directory (e.g., `C:\xampp\htdocs\`).
     - Start the Apache server using the XAMPP control panel.

3. **Verify Installation**

   Ensure that PHP is working by accessing `http://localhost` in your web browser. You should see the XAMPP dashboard or your configured homepage.

## Usage

Access the application by navigating to `http://localhost/PHP-note-taking-app/note_taking_app.php` in your web browser.

### Login

1. **Navigate to the Login Page**

   Upon accessing the application URL, you will be presented with the login form.

2. **Enter Credentials**

   - **Username:** `Yasin`
   - **Password:** `khan`

3. **Login**

   Click the **"Login"** button to access your note dashboard.

   ![Login Page](screenshots/login_page.png)

### Add a New Note

1. **Add Note Form**

   After logging in, you will see a form to add a new note.

2. **Enter Note Details**

   - **Title:** Enter the title of your note.
   - **Content:** Enter the content of your note.

3. **Submit**

   Click the **"Add Note"** button to save your note.

   ![Add Note](screenshots/add_note.png)

### View Notes

1. **Notes List**

   All your existing notes will be displayed in a table format below the add note form.

2. **Note Details**

   Each note displays the following:
   - **ID:** Unique identifier for the note.
   - **Title:** Title of the note.
   - **Content:** Content of the note.
   - **Date Created:** Timestamp of when the note was created.
   - **Actions:** Options to edit or delete the note.

   ![View Notes](screenshots/view_notes.png)

### Edit a Note

1. **Initiate Edit**

   Click the **"Edit"** link next to the note you wish to modify.

2. **Edit Form**

   You will be presented with a form pre-filled with the note's current title and content.

3. **Update Details**

   Modify the title and/or content as desired.

4. **Save Changes**

   Click the **"Update Note"** button to save your changes.

   ![Edit Note](screenshots/edit_note.png)

5. **Cancel Edit**

   If you wish to cancel editing, click the **"Cancel"** button to return to the notes list without saving changes.

### Delete a Note

1. **Initiate Delete**

   Click the **"Delete"** link next to the note you wish to remove.

2. **Confirm Deletion**

   A confirmation prompt will appear. Click **"OK"** to confirm deletion or **"Cancel"** to abort.

   ![Delete Note](screenshots/delete_note.png)

3. **Note Removal**

   Upon confirmation, the note will be removed from the list.

### Logout

1. **Logout Link**

   Click the **"Logout"** link located at the top-right corner of the dashboard.

2. **Session Termination**

   You will be logged out and redirected to the login page.

   ![Logout](screenshots/logout.png)

## Dependencies

- **PHP 7.x or higher**
- **Web Server**: Apache, Nginx, or any compatible server.
- **CSV Files**: Utilizes PHP's built-in CSV handling functions.

## File Structure

```
PHP-note-taking-app/
├── note_taking_app.php
├── users.csv
├── notes.csv
├── README.md
└── screenshots/
    ├── login_page.png
    ├── add_note.png
    ├── view_notes.png
    ├── edit_note.png
    ├── delete_note.png
    └── logout.png
```

- **note_taking_app.php**: Main application script handling all functionalities.
- **users.csv**: Stores user information (username and hashed password).
- **notes.csv**: Stores notes (ID, title, content, date_created).
- **README.md**: This readme file.
- **screenshots/**: Directory containing screenshots of the application for visual guidance.

## Data Persistence

- **Users Data (`users.csv`)**:

  | Username | Password                                    |
  |----------|---------------------------------------------|
  | Yasin    | *Hashed version of "khan" using `password_hash`* |

  - **Note:** The password is securely hashed for authentication purposes.

- **Notes Data (`notes.csv`)**:

  | ID | Title          | Content             | Date Created       |
  |----|----------------|---------------------|--------------------|
  | 1  | Sample Note    | This is a sample... | 2024-04-25 14:30:00|
  | 2  | Another Note   | Another note content| 2024-04-26 09:15:45|
  | ...| ...            | ...                 | ...                |

- **File Initialization**: Upon first access, if `users.csv` or `notes.csv` do not exist, the application creates them with appropriate headers and a predefined user.

## Input Validation

- **User Authentication**:
  - **Username:** Required and must match the predefined username (`Yasin`).
  - **Password:** Required and must match the predefined password (`khan`).

- **Note Management**:
  - **Title:** Cannot be empty when adding or editing a note.
  - **Content:** Cannot be empty when adding or editing a note.

- **Data Sanitization**:
  - User inputs are sanitized using `htmlspecialchars` to prevent XSS attacks.

## Extensibility

This basic note-taking application can be extended with additional features such as:

- **User Registration**: Allow multiple users to register and manage their own notes.
- **Password Reset**: Implement functionality to reset forgotten passwords.
- **Rich Text Editing**: Integrate a rich text editor for formatting note content.
- **Search and Filter**: Add search functionality to find notes by title or content.
- **Categorization**: Organize notes into categories or tags.
- **Exporting Notes**: Enable exporting notes to formats like PDF or TXT.
- **Responsive Design**: Enhance the UI to be fully responsive for mobile devices.
- **Database Integration**: Transition from CSV files to a relational database like MySQL for better scalability and security.
- **Enhanced Security**: Implement measures such as input validation, protection against SQL injection (if using databases), and secure session management.

## Security Considerations

- **Password Storage**: Passwords are hashed using PHP's `password_hash` function for secure storage.
- **Session Management**: User sessions are managed using PHP sessions to maintain authentication state.
- **Input Sanitization**: All user inputs are sanitized to prevent Cross-Site Scripting (XSS) attacks.
- **Access Control**: Only authenticated users can access and manage notes.
- **Limitations**:
  - This application uses CSV files for data storage, which is suitable for small-scale or personal use. For larger applications or multi-user environments, consider using a secure database system.
  - The application currently has a single predefined user. Extending to multiple users requires implementing user registration and management functionalities.

## License

This project is licensed under the [MIT License](LICENSE).

---

**Notes:**

1. **Screenshots Directory**: The README references screenshots stored in a `screenshots/` directory. Ensure you add relevant images to this directory in your repository to enhance visual guidance.

2. **License File**: The README mentions an `LICENSE` file. Make sure to include a `LICENSE` file in your repository, preferably the MIT License as indicated.

3. **Repository Link**: The README is tailored to the repository at `https://github.com/yasinULLAH/PHP-note-taking-app`. Ensure that all file paths and instructions align with your repository's structure.

4. **Customization**: Feel free to customize the README further to match any additional features or specific instructions related to your application.

5. **Security Enhancements**: For production environments, consider implementing additional security measures such as HTTPS, prepared statements (if using databases), and comprehensive input validation.


# Voting Website

## Project Description
The Voting Website is a web application that allows users to participate in and create votes. Users can log in, create votes, and cast their votes for participants in various elections or polls.

## Features
- User Registration: Users can sign up for an account.
- User Authentication: Registered users can log in to access their accounts.
- Create Votes: Logged-in users can create votes, providing details such as vote names, descriptions, start and end dates, and participants.
- View Votes: Users can view available votes and see their details, including start and end dates.
- Vote Casting: Registered users can cast their votes for participants in active votes.
- Voting Results: Users can view the results of ongoing and completed votes.
- Vote Management: Vote creators can manage their votes by extending dates, adding new participants, updating participant data, deleting participants, and withdrawing from votes.

## Requirements
- Web server (e.g., Apache) with PHP support
- MySQL database
- PHP development environment
- A code editor for making changes to the project

## Setup Instructions
1. Clone or download the project to your web server's root directory.
2. Create a MySQL database and import the provided SQL file to create the necessary tables.
3. Update the database connection details in the PHP code (see code comments for details).
4. Start a session by including `session_start();` at the beginning of your PHP files.
5. Ensure that your web server is running, and the necessary PHP modules are enabled.
6. Access the Voting Website by visiting the appropriate URL in your web browser.

## Usage
- Register or log in to your account to create, manage, and participate in votes.
- Create votes with detailed information about the vote, participants, and date.
- View available votes and cast your votes during the specified timeframe.
- Manage your votes by extending dates, adding participants, updating participant data, deleting participants, and withdrawing from votes.

## Contributing
Contributions to this project are welcome. If you have suggestions, bug reports, or feature requests, please submit them as issues in the project's GitHub repository.

## License
This project is licensed under the MIT License. You are free to use, modify, and distribute it as per the license terms.

Thank you for using the Voting Website! If you have any questions or need assistance, feel free to reach out to the project maintainers.

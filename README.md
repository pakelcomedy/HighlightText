# Highlight Text Detection

This project is a web application for detecting and storing highlighted words from text. The application uses Flask as the backend and stores data in MySQL.

## Project Structure

```
/text_highlighter_project
│
├── db_config.php         # Configuration file for database connection
│
├── index.php             # Main page
│
├── save_text.php         # Script to process text saving
```

## Installation

1. **Clone Repository**:  
   ```bash
   git clone https://github.com/pakelcomedy/HighlightText.git  
   cd highlight-text-detection  
   ```

2. **Set Up Database**:  
   - Access MySQL and create the necessary database and tables by running the following commands:  
   ```sql
   CREATE DATABASE IF NOT EXISTS highlight;

   USE highlight;

   CREATE TABLE IF NOT EXISTS highlighted_texts (
       id INT AUTO_INCREMENT PRIMARY KEY,
       text VARCHAR(255) NOT NULL,
       indices TEXT NOT NULL
   );
   ```

## Running the Application

## How to Use

- Highlight the text you want to save.  
- Click the "Save Highlighted Text" button.  
- Each highlighted word will be saved in the database along with its occurrence indices.

## Contributing

If you would like to contribute to this project, please open an issue or submit a pull request.

## License

This project is licensed under the [GPL License](LICENSE).

# inec-app
This repository contains INEC elections monitoring design with php, mysql and nginx web server to monitor election progress from polling units to ward to local government and state levels




# INEC Polling Unit Results System

A comprehensive PHP MVC web application for managing and analyzing election results from Nigeria's Independent National Electoral Commission (INEC).


# Features


· Polling Unit Results: View detailed results for individual polling units
· LGA Results: View summed results for all polling units within a Local Government Area
· State Results Comparison: Compare announced LGA results with calculated results from polling units
· New Result Entry: Add new polling unit results to the system
· Data Integrity Verification: Check for discrepancies between announced and calculated result

# Setup Instructions


clone this repository and cd not it

1. Make scripts executable:
   ```bash
   chmod +x setup.sh start.sh stop.sh
   ```
2. With Docker (Recommended):
   ```bash
   ./setup.sh
   ```
3. Without Docker:
   ```bash
   ./start.sh
   ```
4. Access the application:
   · Main app: http://localhost
   · PHPMyAdmin: http://localhost:8080 (Docker only)

This structure provides a complete, self-contained application with both Docker and traditional deployment


# Database Structure

The application uses the following main tables:

· polling_unit - Information about polling units
· announced_pu_results - Results from individual polling units
· lga - Local Government Area information
· announced_lga_results - Announced results at LGA level
· states - State information
· party - Political party information

Technical Details

· Architecture: MVC (Model-View-Controller)
· Frontend: HTML5, CSS3, JavaScript (vanilla)
· Backend: PHP 7+
· Database: MySQL
· Security: Input sanitization, PDO prepared statements



Support

For questions, issues, or contributions, please contact:

· Email: fadilmustapha64@gmail.com
· Website: https://fadil59.pythonanywhere.com/

License

This project is open source and available under the MIT License.

Contributing

Contributions are welcome! Please feel free to submit a Pull Request.




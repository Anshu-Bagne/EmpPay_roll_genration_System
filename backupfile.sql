CREATE DATABASE a_payrollsystem_v2;
USE a_payrollsystem_v2;

CREATE TABLE departments (

    id INT AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(100) NOT NULL UNIQUE,

    created DATETIME DEFAULT CURRENT_TIMESTAMP,

    modified DATETIME DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP

);
CREATE TABLE designations (

    id INT AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(100) NOT NULL UNIQUE,

    created DATETIME DEFAULT CURRENT_TIMESTAMP,

    modified DATETIME DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP

);
CREATE TABLE employees (

    id INT AUTO_INCREMENT PRIMARY KEY,

    employee_code VARCHAR(20) NOT NULL UNIQUE,

    department_id INT NOT NULL,

    designation_id INT NOT NULL,

    name VARCHAR(150) NOT NULL,

    base_salary DECIMAL(10,2) NOT NULL,

    joining_date DATE NOT NULL,

    email VARCHAR(150) NOT NULL UNIQUE,

    mobile VARCHAR(15) NOT NULL UNIQUE,

    status ENUM('active','inactive') DEFAULT 'active',

    created DATETIME DEFAULT CURRENT_TIMESTAMP,

    modified DATETIME DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_employee_department
        FOREIGN KEY (department_id)
        REFERENCES departments(id),

    CONSTRAINT fk_employee_designation
        FOREIGN KEY (designation_id)
        REFERENCES designations(id)

);

CREATE TABLE attendances (

    id INT AUTO_INCREMENT PRIMARY KEY,

    employee_id INT NOT NULL,

    attendance_date DATE NOT NULL,

    status ENUM('present','leave','absent') NOT NULL,

    created DATETIME DEFAULT CURRENT_TIMESTAMP,

    modified DATETIME DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_attendance_employee
        FOREIGN KEY (employee_id)
        REFERENCES employees(id)
        ON DELETE CASCADE,

    CONSTRAINT uq_employee_attendance
        UNIQUE(employee_id, attendance_date)

);

CREATE TABLE payslips (

    id INT AUTO_INCREMENT PRIMARY KEY,

    employee_id INT NOT NULL,

    payroll_month TINYINT NOT NULL,

    payroll_year YEAR NOT NULL,

    working_days INT NOT NULL,

    present_days INT NOT NULL,

    leave_days INT NOT NULL,

    absent_days INT NOT NULL,

    base_salary DECIMAL(10,2) NOT NULL,

    salary_earned DECIMAL(10,2) NOT NULL,

    bonus_total DECIMAL(10,2) DEFAULT 0,

    deduction_total DECIMAL(10,2) DEFAULT 0,

    net_salary DECIMAL(10,2) NOT NULL,

    payment_date DATE NOT NULL,

    created DATETIME DEFAULT CURRENT_TIMESTAMP,

    modified DATETIME DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_payslip_employee
        FOREIGN KEY (employee_id)
        REFERENCES employees(id),

    CONSTRAINT uq_employee_payroll
        UNIQUE(employee_id, payroll_month, payroll_year)

);

CREATE TABLE bonuses (

    id INT AUTO_INCREMENT PRIMARY KEY,

    payslip_id INT NOT NULL,

    type VARCHAR(100) NOT NULL,

    amount DECIMAL(10,2) NOT NULL,

    remarks VARCHAR(255),

    created DATETIME DEFAULT CURRENT_TIMESTAMP,

    modified DATETIME DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_bonus_payslip
        FOREIGN KEY (payslip_id)
        REFERENCES payslips(id)
        ON DELETE CASCADE

);

CREATE TABLE deductions (

    id INT AUTO_INCREMENT PRIMARY KEY,

    payslip_id INT NOT NULL,

    type VARCHAR(100) NOT NULL,

    amount DECIMAL(10,2) NOT NULL,

    remarks VARCHAR(255),

    created DATETIME DEFAULT CURRENT_TIMESTAMP,

    modified DATETIME DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_deduction_payslip
        FOREIGN KEY (payslip_id)
        REFERENCES payslips(id)
        ON DELETE CASCADE

);


desc bonuses;


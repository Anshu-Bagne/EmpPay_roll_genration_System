create database a_payrollsystem;

use a_payrollsystem;

ALTER DATABASE a_payrollsystem 
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

CREATE TABLE departments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
);
INSERT INTO departments(name)VALUES
('HR'),('IT'),('Finance'),('Marketing'),('Operations'),('Administration');

CREATE TABLE designations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
);
INSERT INTO designations(name)VALUES
('Software Developer'),('Senior Developer'),('HR Executive'),('Accountant'),
('Manager'),('Team Lead'),('QA Engineer'),('System Administrator');

CREATE TABLE employees (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    emp_id VARCHAR(20) NOT NULL UNIQUE COMMENT 'Business Employee ID (e.g., EMP0001)',
    name VARCHAR(100) NOT NULL,
    department_id INT UNSIGNED NOT NULL,
    designation_id INT UNSIGNED NOT NULL,
    base_salary DECIMAL(12,2) NOT NULL,
    joining_date DATE NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    mobile VARCHAR(15) NOT NULL UNIQUE,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
   
   CONSTRAINT fk_employee_department
        FOREIGN KEY (department_id)
        REFERENCES departments(id) ON UPDATE CASCADE ON DELETE RESTRICT,

    CONSTRAINT fk_employee_designation
        FOREIGN KEY (designation_id)
        REFERENCES designations(id) ON UPDATE CASCADE ON DELETE RESTRICT,

    CHECK (base_salary > 0)
);

ALTER TABLE employees
CHANGE COLUMN emp_id employee_code VARCHAR(20) NOT NULL UNIQUE;

CREATE TABLE attendances (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    employee_id INT UNSIGNED NOT NULL,
    attendance_date DATE NOT NULL,
    status ENUM('present','absent','leave') NOT NULL,
    created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_attendance_employee
	FOREIGN KEY(employee_id) REFERENCES employees(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    
    CONSTRAINT uq_employee_attendance UNIQUE(employee_id, attendance_date)
);

CREATE TABLE payslips (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    employee_id INT UNSIGNED NOT NULL,
    payroll_month TINYINT UNSIGNED NOT NULL,
    payroll_year YEAR NOT NULL,
    working_days INT UNSIGNED NOT NULL DEFAULT 0,
    present_days INT UNSIGNED NOT NULL DEFAULT 0,
    base_salary DECIMAL(12,2) NOT NULL,
    bonus_total DECIMAL(12,2) NOT NULL DEFAULT 0,
    deduction_total DECIMAL(12,2) NOT NULL DEFAULT 0,
    net_salary DECIMAL(12,2) NOT NULL,
    payment_date DATE NOT NULL,
    created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
   
   CONSTRAINT fk_payslip_employee
        FOREIGN KEY(employee_id)REFERENCES employees(id) ON UPDATE CASCADE ON DELETE RESTRICT,

    CONSTRAINT uq_employee_payroll
        UNIQUE(employee_id, payroll_month, payroll_year),

    CHECK (payroll_month BETWEEN 1 AND 12)
);

CREATE TABLE bonuses (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    payslip_id INT UNSIGNED NOT NULL,
    type ENUM('Performance','Festival') NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_bonus_payslip
        FOREIGN KEY(payslip_id)REFERENCES payslips(id) ON UPDATE CASCADE ON DELETE CASCADE,
    CHECK(amount >= 0)
);

CREATE TABLE deductions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    payslip_id INT UNSIGNED NOT NULL,
    type ENUM('TDS','Unpaid Leave') NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_deduction_payslip
        FOREIGN KEY(payslip_id) REFERENCES payslips(id) ON UPDATE CASCADE ON DELETE CASCADE,
    CHECK(amount >= 0)
);





# URL-Shorterner
### INFORMATION
url shorterner with xampp
### TUTORIALS
* https://www.mediafire.com/file/u8kob5poqo2juwl/Project_07-01_Full+HD+1080p_MEDIUM_FR50.mp4/file
```bash
CREATE TABLE short_links (
    id INT AUTO_INCREMENT PRIMARY KEY,
    short_code VARCHAR(64) NOT NULL UNIQUE,
    original_url TEXT NOT NULL,
    clicks INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE access_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    short_code VARCHAR(64),
    ip_address VARCHAR(45),
    user_agent TEXT,
    accessed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

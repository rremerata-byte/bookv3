# 📖 Quick Reference: History Report Feature

## How to Use

### Filter by Date Range

1. **Go to**: Available Books → Requests Tab → History Section
2. **Set Dates**:
   - Select **From Date** (e.g., 11/01/2024)
   - Select **To Date** (e.g., 11/08/2024)
3. **View Results**: Table and statistics update automatically

### Search History

1. **Type** in the search box: book name, user name, or "borrow"/"reserve"
2. **Results** update instantly

### Filter by Status

1. **Click** the status dropdown
2. **Select**: All Status, Approved, Rejected, or Returned
3. **View** filtered results

### Download Report

1. **Apply filters** (optional - dates, status, search)
2. **Click** the green "Download Report" button
3. **Check** your Downloads folder for the CSV file

### Clear All Filters

1. **Click** the "Clear" button (rightmost in filter row)
2. **All filters reset** to default (showing all records)

## Report Statistics Explained

| Statistic | Meaning |
|-----------|---------|
| **Approved** | Requests that were approved by admin |
| **Rejected** | Requests that were rejected |
| **Returned** | Books that have been returned after borrowing |
| **Borrowed** | Total number of borrow requests (regardless of status) |
| **Reserved** | Total number of reservation requests |
| **Total** | Total number of filtered records |

## CSV File Contents

Your downloaded report includes:
- ✅ All filtered request records
- ✅ Complete book and user information
- ✅ Summary statistics section
- ✅ Date range information (if applied)
- ✅ Report generation timestamp

## Tips & Tricks

💡 **Monthly Reports**: Set dates to first and last day of month  
💡 **Daily Summary**: Set both dates to today  
💡 **User Activity**: Use search box with user's name  
💡 **Returned Books**: Use status filter + date range  
💡 **Quick Export**: No filters = export all history  

## Keyboard Shortcuts

- `Tab` - Navigate between filter inputs
- `Enter` - Confirm date selection
- `Esc` - Close date picker

## File Naming Convention

- **With dates**: `book_requests_report_20241101_to_20241108.csv`
- **Without dates**: `book_requests_report_20241108.csv`

---

**Need Help?** Check HISTORY_REPORT_FEATURES.md for detailed documentation.

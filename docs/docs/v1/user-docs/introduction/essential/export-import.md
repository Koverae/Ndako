# Export and import data

In Ndako, it is sometimes necessary to export or import data for running reports, or for data modification. This document covers the export and import of data into and out of Ndako.

> Sometimes, users run into a "time out" error, or a record does not process, due to its size. This can occur with large exports, or in case where the import file is too large. To circumvent this limitation surrounding the size of the records, process exports or imports in smaller batches.

## Export data from Ndako
When working with a database, it is sometimes necessary to export data in a distinct file. Doing so can aid in reporting on activities, although, Ndako provides a precise and easy reporting tool with each available application.

With Ndako, the values can be exported from any field in any record. To do so, activate the list view ( `list` icon), on the items that need to be exported, then select the records that should be exported. To select a record, tick the checkbox next to the corresponding record. Finally, click on  **Actions**, then **Export**.

![Export](/screenshots/ndako-export-selected.png)

When clicking on **Export**, a `.xlsx` file containing your data will be downloaded on your device.

## Import data into Ndako
Importing data into Ndako is extremely helpful during implementation, or in times where data needs to be **updated in bulk**. The following documentation covers how to import data into an Ndako database.

> Imports are permanent and **cannot** be undone. However, it is possible to use filters (`created on` or `last modified`) to identify records changed or created by the import.

Data can be imported on any Ndako business object using either Excel (`.xlsx`) or CSV (`.csv`) formats. This includes: guests, properties, units(rooms), reservations, payments, expenses, and users.

Open the view of the object to which the data should be imported/populated, click the  (**gear**) icon and select **Import records**.

![Import](/screenshots/ndako-import-records.png)

Click **Import Template for Units** at the center of the page to download a **template** and populate it with the company’s own data. Such templates can be imported in one click since the data mapping is already preconfigured.

To upload the downloaded **template** or your own file, follow the next steps:

- Click **Upload Data File** and select the desired file.

- Adjust the **Formatting** options as needed (for CSV files only).

- Ensure all data in the **File Column** is correctly mapped to the appropriate Odoo Field and free of errors.

- (Optional) Click **Load Data File** to reload the same file or upload a different one.

- Check up the **preview table** to ensure the data have been processed

- Click **Import**.

![Import](/screenshots/ndako-import-page.png)

![Import](/screenshots/ndako-import-page-2.png)
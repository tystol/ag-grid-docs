<?php
$key = "Data Export";
$pageTitle = "ag-Grid Data Export";
$pageDescription = "Data in the grid can be exported to csv. This page explains how to do exports along with properties you can set to get the exports done.";
$pageKeyboards = "ag-Grid Data Export Javascript CSV";
include '../documentation-main/documentation_header.php';
?>

<div>

    <h2>Data Export</h2>

    <p>
        The data can be exported to CSV with an API call. You have two options, let the grid do the export if the
        browser is modern and it is allowed, or you get the grid to return you the CSV string and your application
        is responsible for the export (some older browsers will require you to send the data to the server and do
        an old school 'file download' from the server).
    </p>

    <p>
        The API methods are as follows:
    </p>
    <ul>
        <li><b>exportDataAsCsv(params)</b>: Does the full export.</li>
        <li><b>getDataAsCsv(params)</b>: Returns the CSV data for export.</li>
    </ul>

    <p>
        Each of these methods takes a an optional params object that can take the following:
    </p>
    <ul>
        <li><b>skipHeader</b>: Set to true if you don't want to first line to be the column header names.</li>
        <li><b>skipGroups</b>: Set to true to skip headers and footers if grouping. No impact if not grouping.</li>
        <li><b>skipFooters</b>: Set to true to skip footers only if grouping. No impact if not grouping or if not using footers in grouping.</li>
        <li><b>suppressQuotes</b>: Set to true to not use double quotes between values.</li>
        <li><b>fileName</b>: String to use as the file name. If missing, the file name 'export.csv' will be used.</li>
        <li><b>customHeader</b>: If you want to put some text at the top of the csv file, stick it here.
            You will need to include '\n' at the end, or many '\n' if you want the header to span lines.</li>
        <li><b>customFooter</b>: Same as customHeader, but for the end of the file.</li>
        <li><b>allColumns</b>: If true, all columns will be exported in the order they appear in columnDefs.
            Otherwise only the columns currently showing the in grid, and in that order, are exported.</li>
        <li><b>onlySelected</b>: Only export selected rows.</li>
        <li><b>onlySelectedAllPages</b>: Only export selected rows including other pages (only makes sense when using pagination).</li>
        <li><b>columnSeparator</b>: The column separator. Defaults to comma.</li>
        <li><b>columnKeys</b>: Provide a list (an array) of column keys if you want to export specific columns.</li>
        <li><b>processCellCallback</b>: Allows you to process (typically format) cells for the CSV.</li>
        <li><b>processHeaderCallback</b>: Allows you to create custom header values for the export.</li>
    </ul>

    <h3>processCellCallback()</h3>

    <p>This callback allows you to format the cells for the export. The example below has an option 'Use Cell Callback'
    which puts all the items into upper case. This can be useful if, for example, you need to format date cells
    to be read by Excel.</p>

    <p>
        The callback params has the following attributes: value, node, column, api, columnApi, context.
    </p>

    <h3>processHeaderCallback()</h3>

    <p>If you don't like the header names the grid provides, then you can provide your own header names. Maybe you
        have grouped columns and you want to include the columns parent groups.</p>

    <p>
        The callback params has the following attributes: column, api, columnApi, context.
    </p>

    <h3>
        What Gets Exported
    </h3>

    <p>
        The same data that is in the grid gets exported, but none of the GUI representation of the data will be.
        What this means is:
        <ul>
            <li>The raw values, and not the result of cellRenderer, will get used, meaning:
                <ul>
                    <li>cellRenderers will NOT be used.</li>
                    <li>valueGetters will be used.</li>
                    <li>cellFormatters will NOT be used (use <i>processCellCallback</i> instead).</li>
                </ul>
            </li>
            <li>If row grouping, all data will be exported regardless of groups open or closed.</li>
            <li>If row grouping with footers (groupIncludeFooter=true) the footers will NOT be used -
                this is a GUI addition that happens for displaying the data in the grid.</li>
        </ul>
    </p>

    <h3>
        Example
    </h3>
    <p>
        The example below shows the export in action. Notice the following:
    </p>
    <ul>
        <li>Filtered rows are not included in the export.</li>
        <li>The sort order is maintained in the export.</li>
        <li>The order of the columns is maintained in the export.</li>
        <li>Only visible columns are export.</li>
        <li>Value getters are used to work out the value to export (the 'Group' col in the example below uses a value getter to take the first letter of the country name)</li>
        <li>Aggregated values are exported.</li>
        <li>For groups, the first exported value (column) will always have the group key.</li>
    </ul>

    <show-example example="exampleExport"></show-example>

</div>

<?php include '../documentation-main/documentation_footer.php';?>
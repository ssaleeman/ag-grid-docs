<?php
$key = "Refresh";
$pageTitle = "ag-Grid Refresh";
$pageDescription = "It is possible to refresh ag-Grid in many ways. This page explains how to refresh cells inside the grid.";
$pageKeyboards = "ag-Grid Refresh";
$pageGroup = "feature";
include '../documentation-main/documentation_header.php';
?>

<div>

    <h1 class="first-h1">View Refresh</h1>

    <p>
        The grid has change detection. So as long as you are updating the data via the grid's API,
        the values displayed should be the most recent up to date values.
    </p>

    <p>
        However sometimes you may be updating the data outside of the grids control. When
        you give data to the grid, the grid will not make a copy. Thus if you change the value
        of the data outside of the grid, the grid will also be impacted by that data change.
    </p>

    <p>
        To deal with the scenario where the row data is changed without the grid been aware,
        the grid provides the following methods:
        <ul>
            <li>
                <b>Refresh Cells</b>: <code>api.refreshCells(cellRefreshParams)</code> - Gets the grid to refresh all cells. Change detection will
                be used to refresh only cells who's display cell values are out of sync with the actual value.
                If using a <a href="../javascript-grid-cell-rendering-components/">cellRenderer</a> with a refresh
                method, the refresh method will get called.
            </li>
            <li>
                <b>Redraw Rows</b>: <code>api.redrawRows(redrawRowsParams)</code> - Removes the rows from the DOM and draws them again from scratch.
                The cells are created again from scratch. No change detection is done. No refreshing of cells
                is done.
            </li>
        </ul>
    </p>

    <p>
        Your preference should be to use <code>refreshCells()</code> over <code>redrawRows()</code>.
        Only use <code>redrawRows()</code> if you find <code>refreshCells()</code> doesn't
        suit your needs.
    </p>

    <h1>Refresh Cells</h1>

    <p>
        To get the grid to refresh the cells, call <code>api.refreshCells()</code>. The interface is as follows:
    </p>

    <pre><span class="codeComment">// method for refreshing cells</span>
function refreshCells(params: RefreshCellsParams = {}): void;

<span class="codeComment">// params for refresh cells</span>
interface RefreshCellsParams {
    rowNodes?: RowNode[]; <span class="codeComment">// specify rows, or all rows by default</span>
    columns?: (string|Column)[]; <span class="codeComment">// specify columns, or all columns by default</span>
    force?: boolean; <span class="codeComment">// skips change detection, refresh everything</span>
    volatile?: boolean; <span class="codeComment">// only volatile cells - deprecated - for backwards compatibility</span>
}</pre>

    <p>
        Each parameter is optional. The simplest is to call with no parameters which will refresh
        all cells using <a href="../javascript-grid-change-detection/">change detection</a> (change
        detection means it will only refresh cells who's values have changed).
    </p>

    <note>
        <h3>Deprecated - Volatile Columns</h3>

        <p>
            Volatile columns allow you to mark specific columns for refresh when you call
            <code>api.refreshCells()</code>.
        </p>

        <p>
            Columns are marked as volatile by setting the column definition property
            <code>volatile = true</code>.
        </p>

        <p>
            This feature is no longer needed, as you can pass a list of columns to refresh
            to the 'cellRefresh()' method.
        </p>

        <p>
            If you are using volatile columns, instead of calling <code>api.softRefresh()</code>,
            can call <code>api.refreshCells({volatile: true})</code> instead to achieve the same.
            However volatile columns are deprecated so will be removed in a future release.
            You should instead move to passing a list of columns to the <code>api.softRefresh()</code>
            method.
        </p>

    </note>

    <h3>Example Refresh Cells</h3>

    <p>
        Below shows calling <code>api.refreshCells()</code> with different scenarios using a mixture of the
        <code>rowNodes</code>, <code>columns</code> and <code>force</code> parameters. From the example, the
        following can be noted:
    </p>

    <ul>
        <li>
            The grid has <code>enableCellChangeFlash=true</code>, so cells that are refreshed will be flashed.
        </li>
        <li>
            The grid has two pinned rows at the top and two pinned rows at the bottom. This is to demonstrate
            that cell refreshing works for pinned rows also.
        </li>
        <li>
            The three buttons each make use of a <b>scramble</b> operation. The scramble operation selects
            50% of the cells at random and assigns new values to them. This is done outside of the grid so the
            grid has not been informed of the data changes. Each button then gets the grid to refresh in a
            different way.
        </li>
        <li>
            The <b>Scramble & Refresh All</b> button will scramble the data, then call
            <code>api.refreshCells()</code>. You will notice that randomly half the cells will flash as
            the change detection only update the cells who's underlying values have changed.
        </li>
        <li>
            The <b>Scramble & Refresh Left to Right</b> button will scramble as before, then call
            <code>api.refreshCells({columns})</code> 5 times, 100ms apart, once for each column. This will show the
            grid refreshing one column at a time from left to right.
        </li>
        <li>
            The <b>Scramble & Refresh Top to Bottom</b> button will scramble as before, then call
            <code>api.refreshCells({rowNodes})</code> 20 times, 100ms apart, once for each row (including pinned rows).
            This will show the grid refreshing one row at a time from top to bottom.
        </li>
        <li>
            The checkbox <b>Force Refresh</b> sets how the above three refreshes work. If checked, all the cells
            will get refreshed regardless of whether they have changes. In other words, change detection will not
            but used as part of the refresh.
        </li>
    </ul>

    <show-example example="exampleRefreshApi"></show-example>

    <note>
        You may be wondering why would you want to force refresh, what is the point in refreshing a cell that
        has no changes? The answer is to do with cells that don't show underlying data or depend on something other
        than just the underlying data. One example is a cell that might contain action buttons (add, delete, send etc)
        and you might want to disable the action buttons if the logged in user changes role (if roles are tied to the
        functions), or if it's past 5pm and you don't want to allow such operations past a certain time. In this case
        you may wish to update the cells even though the underlying data has not changed.
    </note>

    <h1>Redraw Rows</h1>

    <p>
        Redraw rows is a much heavier operation than refreshing cells. If refreshing cells meets your needs, then don't
        use redraw rows. A row redraw will rip the row out of the DOM and draw it again from scratch.
    </p>

    <p>
        Use redraw row if you want to create the row again from scratch. This is useful when you have changed a property
        that only gets used when the row is created for the first time such as:
        <ul>
            <li>
                Whether the row is <a href="../javascript-grid-full-width-rows">fullWidth</a> or not.
            </li>
            <li>
                The cellRenderer used for any cell (as this is specified once when the cell is created).
            </li>
            <li>
                You want to specify different styles for the row via the callbacks <code>getRowStyle()</code>
                or <code>getRowClass()</code>.
            </li>
        </ul>
    </p>

    <p>
        To get the grid to redraw rows, call <code>api.redrawRows()</code>. The interface is as follows:
    </p>

    <pre><span class="codeComment">// method for redraw rows</span>
function redrawRows(params: RedrawRowsParams = {})

<span class="codeComment">// params for redraw rows</span>
interface RedrawRowsParams {
    rowNodes?: RowNode[]; <span class="codeComment">// the row nodes to redraw</span>
}</pre>

    <h3>Example Redraw Nodes</h3>

    <p>
        Below shows calling <code>api.redrawRows()</code> with different to change the background color of the rows.
        From the example, the following can be noted:
    </p>

    <ul>
        <li>
            The <b>Redraw All Rows</b> redraws all the rows using a different background color by calling
            <code>api.redrawRows()</code> with no parameters.
        </li>
        <li>
            The <b>Redraw Top Rows</b> redraws only the top half of the rows by calling <code>api.redrawRows({rowNodes})</code>.
        </li>

    </ul>

    <show-example example="exampleRedrawRows"></show-example>

</div>

<?php include '../documentation-main/documentation_footer.php';?>

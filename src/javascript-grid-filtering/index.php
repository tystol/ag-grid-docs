<?php
$key = "Filtering";
$pageTitle = "ag-Grid Filtering";
$pageDescription = "ag-Grid Filtering";
$pageKeyboards = "ag-Grid Filtering";
include '../documentation-main/documentation_header.php';
?>

<div>

    <h2>Filtering</h2>

    <p>
        You have two options for filtering, one is use one of the default built-in filters (easy but restricted to
        what's provided), or bake your own custom filters (no restrictions, build what you want, but takes more time).
    </p>

    <note>
        This page discusses filtering outside of the context of paging. To see how to implement server
        side filtering, see the sections
        <a href="/javascript-grid-pagination/index.php">pagination</a>
        and
        <a href="/javascript-grid-virtual-paging/index.php">virtual paging</a>
    </note>

    <h3>Enable Filtering</h3>

    <p>
        Enable filtering by setting grid property <i>enableFilter=true</i>. This turns on filtering on all columns.
        To turn off filtering for particular columns, set <i>suppressFilter=true</i> on the individual column definition.
    </p>

    <p>
        When a filter is active on a column, the filter icon appears before the column name in the header.
    </p>

    <h3>Default Built-In Filters</h3>

    <p>
        The following filter options can be set for a column definition:
    </p>

    <table class="table">
        <tr>
            <th>Filter</th>
            <th>Description</th>
        </tr>
        <tr>
            <th>number</th>
            <td>A number comparison filter. Functionality for matching on {equals, less than, greater than}.</td>
        </tr>
        <tr>
            <th>text</th>
            <td>A string comparison filter. Functionality for mating on {contains, starts with, ends with, equals}.</td>
        </tr>
        <tr>
            <th>set</th>
            <td>A set filter, influenced by how filters work in Microsoft Excel. This is an ag-Grid-Enterprise
                feature and explained further <a href="../javascript-grid-set-filtering/">here</a></td>
        </tr>
    </table>

    <p>
        If no filter type is specified, the default 'text' filter is used (unless you are using ag-Grid-Enterprise,
        in which case the 'set' filter is the default).
    </p>

    <h3>Filter Parameters</h3>

    <p>
        As well as specifying the filter type, you can also provide setup parameters for the filters by setting
        <code>colDef.filterParams</code>. The available parameters are specific to the filter type. What follows
        is an example of setting 'apply=true' and 'newRowsAction=keep' on a text filter:
    </p>

    <pre>
columnDefinition = {
    headerName: "Athlete",
    field: "athlete",
    filter: 'text',
    filterParams: {apply: true, newRowsAction: 'keep'}
}</pre>

    <h4>Text and Number Filter Parameters</h4>
    <p>
        The filter parameters for text and number filter have the following meaning:
        <ul>
            <li><b>newRowsAction:</b> What to do when new rows are loaded. The default is to reset the filter,
                to keep it in line with 'set' filters. If you want to keep the selection, then set this value
                to 'keep'.</li>
            <li><b>apply:</b> Set to true to include an 'Apply' button with the filter and not filter
                automatically as the selection changes.</li>
        </ul>
    </p>

    <h3>Quick Filter</h3>

    <p>
        In addition to the column specific filtering, a 'quick filter' (influenced by how filtering is done in Google
        GMail) can also be applied. Set the quick filter text into the grid options and tell the grid, via the API,
        to filter the data (which will include the new quick filter).
    </p>

    <h4>How it Works</h4>

    <p>
        Each node gets a <i>quick filter text</i> attached to it by concatenating all the values for each column.
        For example, a {Employee Name, Job} table could have a row with quick filter text of 'Niall Crosby_Coffee Maker'.
        The grid then does a simple string search, so if you search for 'Niall', it will find our example text.
        Joining all the columns values into one string gives a huge performance boost. The values
        are joined after the quick filter is requested for the first time and stored in the rowNode - the original
        data that you provide is not changed.
    </p>

    <h4 id="overridingQuickFilter">Overriding the Quick Filter Value</h4>

    <p>
        If your data contains complex objects, then the quick filter will end up with [object,object] inside it
        instead of searchable string values. Or maybe you want to format string values for searching (eg take out
        accent characters, or take out commas from numbers). If you want to do this, then provide a <i>getQuickFilterText</i>
        to the column definition, eg:
        <pre>colDef = {
    headerName: "D", field: "d",
    getQuickFilterText: function(params) {
        return params.value.name;
    }
}</pre>
        Params contains {value, node, data, column, colDef}.
    </p>
    <note>
        You only need to override the quick filter value if you have a problem. If you don't have a quick filter
        problem, you don't need to use it, quick filter will work 'out of the box' in most cases.
    </note>

    <h4>Reset Quick Filters</h4>

    <p>Quick filters can be reset in any of the following ways:
        <ul>
            <li>Each rowNode has a <code>resetQuickFilterAggregateText</code> method on it - call this to reset the quick filter</li>
            <li><code>rowNode.setDataValue(colKey, newValue)</code> will also reset the quick filter</li>
            <li>Lastly, if using the grid editing features, when you update a cell, the quick filter will get reset.</li>
        </ul>
    </p>

    <h4>Quick Filter Example</h4>

    <p>
        The example below shows the quick filter working on different data types. Each column demonstrates something
        different as follows:
        <ul>
            <li>A - Simple column, nothing complex.</li>
            <li>B - Complex object with 'dot' in field, quick filter works fine.</li>
            <li>C - Complex object and value getter used, again quick filter works fine.</li>
            <li>D - Complex object, quick filter would call 'toString' on the complex object, so getQuickFilterText is provided.</li>
            <li>E - Complex object, not getQuickFilterText provided, so the quick filter text ends up with '[object,object]' for this column.</li>
        </ul>
        To see the quick filter text attached to each node, click 'Print Quick Filter Texts' button after you execute
        the quick filter at least one. You will notice the quick filter text is correct for each column except E
        (which would be fixed by adding an appropriate getQuickFilterText method like D does).
    </p>

    <show-example example="exampleQuickFilter"></show-example>

    <h3>Built In Filters Example</h3>

    <p>
        The example below demonstrates:
        <ul>
        <li>Two filter types text filter and number filter.</li>
        <li>Quick Filter</li>
        <li>using the <i>ag-header-cell-filtered</i> class, which is applied to the header
            cell when the header is filtered. By default, no style is applied to this class, the example shows
            applying a different color background to this style.</li>
        <li>'suppressFilter' is set on Total to hide the filter on this column</li>
    </ul>
    </p>

    <show-example example="example1"></show-example>

    <h2>Apply Function</h2>

    <p>
        If you want the user to hit an 'Apply' button before the filter is actioned, add <i>apply=true</i>
        to the filter parameters. The example below shows this in action for the first three columns.
    </p>

    <p>
        This is handy if the filtering operation is taking a long time (usually it doesn't), or if doing
        server side filtering (thus preventing unnecessary calls to the server).
    </p>

    <p>
        The example below also demonstrates the filter hook callbacks (see your browser dev console).
        <li>onFilterModified gets called when the filter changes regardless of the apply button.</li>
        <li>onBeforeFilterChanged gets called before a new filter is applied.</li>
        <li>onAfterFilterChanged gets called after a new filter is applied.</li>
    </p>

    <show-example example="exampleFilterApply"></show-example>

    <h2 id="externalFiltering">External Filtering</h2>

    <p>
        It is common for you to want to have widgets on the top of your grid that influence the grids filtering.
        External filtering allows you to mix your own 'outside of the grid' filtering with the grids filtering.
    </p>

    <p>
        The example below shows external filters in action. There are two methods on gridOptions you
        need to implement: <i>cbIsExternalFilterPresent()</i> and <i>cbDoesExternalFilterPass(node)</i>.
    </p>
    <ul>

    <li>
        <b>isExternalFilterPresent</b> is called exactly once every time the grid senses a filter change.
        It should return true if external filtering is active, otherwise false. If you return true, then
        doesExternalFilterPass() will be called while filtering, otherwise doesExternalFilterPass() will
        not be called.
    </li>
    <li>
        <b>doesExternalFilterPass</b> is called once for each row node in the grid. If you return false,
        the node will be excluded from the final set.
    </li>
    </ul>
    <p>
        If the external filter changes, then you need to call api.onFilterChanged() to tell the grid
    </p>
    <p>
        The example below shows the external filters in action.
    </p>

    <show-example example="exampleFilterExternal"></show-example>

    <h2>Custom Column Filtering</h2>

    <p>
        If the filters provided don't provide what you want, then it's time to build your own filter class.
    </p>

    <p>
        To provide a custom filter, instead of providing a string for the filter in
        the column definition, provide a Filter Component in the form of a function. ag-Grid will call 'new'
        on this function and treat the generated class instance as a filter component. A filter component class
        can be any function / class that implements the following interface:
    </p>

    <pre>interface IFilter {

    <span class="codeComment">// mandatory methods</span>

    <span class="codeComment">// The init(params) method is called on the filter once. See below for details on the parameters.</span>
    init(params: IFilterParams): void;

    <span class="codeComment">// Returns the GUI for this filter. The GUI can be a) a string of html or b) a DOM element or node.</span>
    getGui(): any;

    <span class="codeComment">// This is used to show the filter icon in the header. If true, the filter icon will be shown.</span>
    isFilterActive(): boolean;

    <span class="codeComment">// The grid will ask each active filter, in turn, whether each row in the grid passes. If any
    // filter fails, then the row will be excluded from the final set. The method is provided a
    // params object with attributes node (the rodNode the grid creates that wraps the data) and data
    // (the data object that you provided to the grid for that row).</span>
    doesFilterPass(params: IDoesFilterPassParams): boolean;

    <span class="codeComment">// Gets the filter state for storing</span>
    getModel(): any;

    <span class="codeComment">// Restores the filter state.</span>
    setModel(model: any): void;

    <span class="codeComment">// optional methods</span>

    <span class="codeComment">// Gets called every time the popup is shown, after the gui returned in getGui is attached to the DOM.
    // If the filter popup is closed and reopened, this method is called each time the filter is shown.
    // This is useful for any logic that requires attachment before executing, such as putting focus on a particular DOM
    // element. The params has one callback method 'hidePopup', which you can call at any later
    // point to hide the popup - good if you have an 'Apply' button and you want to hide the popup
    // after it is pressed.</span>
    afterGuiAttached?(params?: {hidePopup?: Function}): void;

    <span class="codeComment">// Gets called when new rows are inserted into the grid. If the filter needs to change it's state
    // after rows are loaded, it can do it here.</span>
    onNewRowsLoaded?(): void;

    <span class="codeComment">// Gets called when the grid is destroyed. If your custom filter needs to do
    // any resource cleaning up, do it here. A filter is NOT destroyed when it is
    // made 'not visible', as the gui is kept to be shown again if the user selects
    // that filter again. The filter is destroyed when the grid is destroyed.</span>
    destroy?(): void;
}</pre>

    <h4>IFilterParams</h4>

    <p>
        The method init(params) takes a params object with the items listed below. If the user provides
        params via the <i>colDef.filterParams</i> attribute, these will be additionally added to the
        params object, overriding items of the same name if a name clash exists.
    </p>

    <pre>interface IFilterParams {

    <span class="codeComment">// The column this filter is for</span>
    column: Column;

    <span class="codeComment">// The column definition for the column</span>
    colDef: ColDef;

    <span class="codeComment">// The row model, helpful for looking up data values if needed.
    // If the filter needs to know which rows are a) in the table b) currently
    // visible (ie not already filtered), c) what groups d) what order - all of this
    // can be read from the rowModel.</span>
    rowModel: IRowModel;

    <span class="codeComment">// A function callback, to be called, when the filter changes,
    // to inform the grid to filter the data. The grid will respond by filtering the data.</span>
    filterChangedCallback: ()=> void;

    <span class="codeComment">// A function callback, to be optionally called, when the filter changes,
    // but before 'Apply' is pressed. The grid does nothing except call
    // gridOptions.filterModified(). This is useful if you are making use of
    // an 'Apply' button and want to inform the user the filters are not
    // longer in sync with the data (until you press 'Apply').</span>
    filterModifiedCallback: ()=> void;

    <span class="codeComment">// A function callback, call with a node to be given the value for that
    // filters column for that node. The callback takes care of selecting the right colDef
    // and deciding whether to use valueGetter or field etc. This is useful in, for example,
    // creating an Excel style filer, where the filter needs to lookup available values to
    // allow the user to select from.</span>
    valueGetter: (rowNode: RowNode) => any;

    <span class="codeComment">// A function callback, call with a node to be told whether
    // the node passes all filters except the current filter. This is useful if you want
    // to only present to the user values that this filter can filter given the status
    // of the other filters. The set filter uses this to remove from the list, items that
    // are no longer available due to the state of other filters (like Excel type filtering). </span>
    doesRowPassOtherFilter: (rowNode: RowNode) => boolean;

    <span class="codeComment">// The context for this grid. See section on <a href="../javascript-grid-context/">Context</a></span>
    context: any;

    <span class="codeComment">// If the grid options angularCompileFilters is set to true, then a new child
    // scope is created for each column filter and provided here. Just ignore this if
    // you are not using Angular 1</span>
    $scope: any;
}</pre>

    <h4>IDoesFilterPassParams</h4>

    <p>
        The method doesFilterPass(params) takes the following as a parameter:
    </p>

    <pre>interface IDoesFilterPassParams {

    <span class="codeComment">// The row node in question</span>
    node: RowNode;

    <span class="codeComment">// The data part of the row node in question</span>
    data: any
}</pre>

    <h3>Custom Filter Example</h3>

    <p>
        The example below shows two custom filters. The first is on the Athlete column and the
        second is on the Year column.
    </p>

    <show-example example="example2"></show-example>

    <h2>Accessing Filter Component Instances</h2>

    <p>
        It is possible to access the filter components directly if you want to interact with the specific
        filter. This also works for your own custom filters, where you can
        get a reference to the underlying filtering instance (ie what was created after ag-Grid called 'new'
        on your filter). You get a reference to the filter instance by calling <code>api.getFilterInstance(colKey)</code>.
    </p>
    <pre><span class="codeComment">// Get a reference to the name filter instance</span>
var nameFilterInstance = api.getFilterInstance('name');</pre>
    <p>
        All of the methods in the IFilter interface (described above) are present, assuming the underlying
        filter implements the method. Your custom filters can add their own methods here that ag-Grid will
        not use but your application can use. What these extra methods do is up to you and between your
        customer filter and your application.
    </p>

    <h3>Text Filter Component Methods</h3>
    <p>
        In addition to implementing the IFilter interface, the text filter also provides the following
        API methods:
        <ul>
        <li>getType(): Gets the type.</li>
        <li>setType(string): Sets the type.</li>
        <li>getFilter(): Gets the filter text.</li>
        <li>setFilter(string): Sets the filter text.</li>
        </ul>
    </p>
    <p>
        The available types for the text filter are the strings: 'contains', 'equals', 'notEquals', 'startsWith' and 'endsWith'.
    </p>
    <p>
        So for example, you can set the text of the 'name' filter to start with 'bob' as follows:
    <pre>var nameFilter = api.getFilterInstance('name');
nameFilter.setType('startsWith');
nameFilter.setFilter('bob');</pre>
    </p>

    <p>
        Or alternatively, you could just use the <i>setModel()</i> method as part of the main <i>IFilter</i>
        interface as follows:
    <pre>var nameFilter = api.getFilterInstance('name');
var model = {type: 'startsWith', filter: 'bob'};
nameFilter.setModel(model);</pre>
    </p>

    <h3>Number Filter Component Methods</h3>
    <p>
        Similar to the text filter, the number filter also provides the following API methods:
    <ul>
        <li>getType(): Gets the type.</li>
        <li>setType(string): Sets the type.</li>
        <li>getFilter(): Gets the filter text.</li>
        <li>setFilter(number): Sets the filter text.</li>
    </ul>
    </p>

    <p>
        The available types for the text filter are the strings: 'equals', 'notEqual', 'lessThan', 'lessThanOrEqual', 'greaterThan' and 'greaterThanOrEqual'.
    </p>

    <h3>Example Filter API</h3>

    <p>
        The example below shows controlling the country and age filters via the API.
    </p>

    <p>
        The example also shows 'gridApi.destroyFilter(col)' which completely destroys a filter. Use this is if you want
        a filter to be created again with new initialisation values.
    </p>

    <p>
        (Note: the example uses the <a href="../javascript-grid-set-filtering/">enterprise set filter</a>).
    </p>

    <show-example example="exampleFilterApi"></show-example>

    <h2>Get / Set All Filter Models</h2>

    <p>
        It is possible to get and set the state of <b>all</b> the filters via the api methods <i>gridOptions.api.getFilterModel</i>
        and <i>gridOptions.api.setFilterModel</i>. These methods manage the filters states via the <i>getModel</i> and <i>setModel</i>
        methods of the individual filters.
    </p>
    <p>
        This is useful if you want to save the filter state and apply it at a later
        state. It is also useful for server side filtering, where you want to pass the filter state to the
        server.
    </p>

    <h4>Example Get / Set All Filter Models</h4>

    <p>
        The example below shows getting and setting all the filter models in action. The 'save' and 'restore' buttons
        mimic what you would do to save and restore the state of the filters. The big button (Name = 'Mich%'... etc)
        shows how you can hand craft a model and then set that into the filters.
    </p>

    <p>
        (Note: the example uses the <a href="../javascript-grid-set-filtering/">enterprise set filter</a>).
    </p>

    <show-example example="exampleFilterModel"></show-example>

    <h2 id="reactFiltering">
        <img src="../images/react.png" height="20px"/>
        React Filtering
    </h2>

    <p>
        It is possible to provide a React filter for ag-Grid to use. All of the information above is
        relevant to React filters. This section explains how to apply this logic to your React component.
    </p>

    <p>
        For examples on React filtering, see the
        <a href="https://github.com/ceolter/ag-grid-react-example">ag-grid-react-example</a> on Github.
        In the example, both 'Skills' and 'Proficiency' columns use React filters.</p>
    </p>

    <h3><img src="../images/react_large.png" style="width: 20px;"/> Specifying a React Filter</h3>

    <p>
        If you are using the ag-grid-react component to create the ag-Grid instance,
        then you will have the option of additionally specifying the filters
        as React components.
    </p>

    <pre><span class="codeComment">// create your filter as a React component</span>
class NameFilter extends React.Component {

    <span class="codeComment">// put in render logic, build a nice gui in React</span>
    render() {
        return &lt;span>My Nice Little Filter Gui&lt;/span>;
    }

    <span class="codeComment">// implement the other Filter callbacks</span>
    isFilterActive(params) {
        <span class="codeComment">// do some filter logic</span>
        return filterPass ? true : false;
    }

    <span class="codeComment">// etc etc, more logic, but leaving out for now</span>
}

<span class="codeComment">// then reference the Component in your colDef like this</span>
colDef = {

    <span class="codeComment">// instead of cellRenderer we use cellRendererFramework</span>
    filterFramework: NameFilter

    <span class="codeComment">// specify all the other fields as normal</span>
    headerName: 'Name',
    field: 'firstName',
    ...
}</pre>

    <p>
        By using <i>colDef.filterFramework</i> (instead of <i>colDef.filter</i>) the grid
        will know it's a React component, based on the fact that you are using the React version of
        ag-Grid.
    </p>


    <h3><img src="../images/react_large.png" style="width: 20px;"/> React Props</h3>

    <p>
        The React component will get the 'filter Params' as described above as it's React Props.
        Therefore you can access all the parameters as React Props.

    <pre><span class="codeComment">// React filter Component</span>
class NameFilter extends React.Component {

    <span class="codeComment">// did you know that React passes props to your component constructor??</span>
    constructor(props) {
        super(props);
        <span class="codeComment">// from here you can access any of the props!</span>
        console.log('The field for this filter is ' + props.colDef.field);
    }

    <span class="codeComment">// maybe your filter has a button in it, and when it gets clicked...</span>
    onButtonWasPressed() {
        <span class="codeComment">// all the methods in the props can be called</span>
        this.props.filterChangedCallback();
    }
}</pre>
    </p>

    <h3><img src="../images/react_large.png" style="width: 20px;"/> React Methods / Lifecycle</h3>

    <p>
        All of the methods in the IFilter interface described above are applicable
        to the React Component with the following exceptions:
    <ul>
        <li><i>init()</i> is not used. Instead use the React props passed to your Component.</li>
        <li><i>destroy()</i> is not used. Instead use the React <i>componentWillUnmount()</i> method for
            any cleanup you need to do.</li>
        <li><i>getGui()</i> is not used. Instead do normal React magic in your <i>render()</i> method..</li>
    </ul>

    <p>
        After that, all the other methods (<i>onNewRowsLoaded(), getModel(), setModel()</i> etc) behave the
        same so put them directly onto your React Component.
    </p>

    <h3><img src="../images/react_large.png" style="width: 20px;"/> Accessing the React Component Instance</h3>

    <p>
        ag-Grid allows you to get a reference to the filter instances via the <i>api.getFilterInstance(colKey)</i>
        method. If your component is a React component, then this will give you a reference to the ag-Grid's
        Component which wraps your React Component. Just like Russian Dolls. To get to the wrapped React instance
        of your component, use the <i>getFrameworkComponentInstance()</i> method as follows:
        <pre><span class="codeComment">// lets assume a React component as follows</span>
class NameFilter extends React.Component {

    ... <span class="codeComment">// standard filter methods hidden</span>

    <span class="codeComment">// put a custom method on the filter</span>
    myMethod() {
        <span class="codeComment">// does something</span>
    }
}

<span class="codeComment">// then in your app, if you want to execute myMethod()...</span>
laterOnInYourApplicationSomewhere() {

    <span class="codeComment">// get reference to the ag-Grid Filter component</span>
    var agGridFilter = api.getFilterInstance('name'); <span class="codeComment">// assume filter on name column</span>

    <span class="codeComment">// get React instance from the ag-Grid instance</span>
    var reactFilterInstance = agGridFilter.getFrameworkComponentInstance();

    <span class="codeComment">// now were sucking diesel!!!</span>
    reactFilterInstance.myMethod();
}</pre>
    </p>

<!--    <p>Again it's some magic to get them working. After this, all you need to do is follow the standard
        ag-Grid custom filter interface in your React component. In other words, the methods in the ag-Grid
        custom filter should appear on your components backing object. The example shows all of this in action.</p>
-->
    <h2 id="ng2Filtering">
        <img src="../images/angular2.png" height="20px"/>
        Angular 2 Filtering
    </h2>

    <p>
        It is possible to provide a Angular 2 Component filter for ag-Grid to use. All of the information above is
        relevant to Angular 2 filters. This section explains how to apply this logic to your Angular 2 component.
    </p>

    <p>
        For an example on Angular 2 filtering, see the
        <a href="https://github.com/ceolter/ag-grid-ng2-example">ag-grid-ng2-example</a> on Github.</p>
    </p>

    <h3><img src="../images/angular2_large.png" style="width: 20px;"/> Specifying a Angular 2 Filter</h3>

    <p>
        If you are using the ag-grid-ng2 component to create the ag-Grid instance,
        then you will have the option of additionally specifying the filters
        as Angular 2 components.
    </p>

    <pre ng-non-bindable><span class="codeComment">// create your filter as a Angular 2 component</span>
@Component({
    selector: 'filter-cell',
    template: `
        Filter: &lt;input style="height: 10px" #input (ngModelChange)="onChange($event)" [ngModel]="text">
    `
})
class PartialMatchFilterComponent implements AgFilterComponent {
    private params:IFilterParams;
    private valueGetter:(rowNode:RowNode) => any;
    private text:string = '';

    @ViewChild('input', {read: ViewContainerRef}) private input;

    agInit(params:IFilterParams):void {
        this.params = params;
        this.valueGetter = params.valueGetter;
    }

    isFilterActive():boolean {
        return this.text !== null && this.text !== undefined && this.text !== '';
    }

    doesFilterPass(params:IDoesFilterPassParams):boolean {
        return this.text.toLowerCase()
            .split(" ")
            .every((filterWord) => {
                return this.valueGetter(params.node).toString().toLowerCase().indexOf(filterWord) >= 0;
            });
    }

    getModel():any {
        return {value: this.text};
    }

    setModel(model:any):void {
        this.text = model.value;
    }

    afterGuiAttached(params:IAfterFilterGuiAttachedParams):void {
        this.input.element.nativeElement.focus();
    }

    componentMethod(message:string) : void {
        alert(`Alert from PartialMatchFilterComponent ${message}`);
    }

    onChange(newValue):void {
        if (this.text !== newValue) {
            this.text = newValue;
            this.params.filterChangedCallback();
        }
    }
}

<span class="codeComment">// then reference the Component in your colDef like this</span>
colDef = {

    <span class="codeComment">// instead of cellRenderer we use cellRendererFramework</span>
    filterFramework: {
        component: PartialMatchFilterComponent,
        moduleImports: [FormsModule]
    },

    <span class="codeComment">// specify all the other fields as normal</span>
    headerName: 'Name',
    field: 'firstName',
    ...
}</pre>

    <p>Your Angular 2 components need to implement <code>AgFilterComponent</code>. The ag Framework expects to find the
        mandatory methods on the interface on the created component (and will call optional methods if they're present).</p>

    <p>When specifying Angular 2 Components you can optionally specify Component dependencies, as well as which modules you wish to import.
        The latter is important if your component uses built in Angular 2 components (such as ngIf, ngStyle etc).</p>

    <pre>
filterFramework: {
    component: YourComponent,

    <span class="codeComment">// dependencies are optional - these go into module.declarations</span>
    dependencies: [YourChildComponent1, YourChildComponent2],

    <span class="codeComment">// module imports are optional - these go into module.imports</span>
    moduleImports: [CommonModule, FormsModule]
}
</pre>

    <p>
        By using <i>colDef.filterFramework</i> (instead of <i>colDef.filter</i>) the grid
        will know it's a Angular 2 component, based on the fact that you are using the Angular 2 version of
        ag-Grid.
    </p>

    <h3><img src="../images/angular2_large.png" style="width: 20px;"/> Angular 2 Params</h3>

    <p>The ag Framework expects to find the <code>agInit</code> (on the <code>AgFilterComponent</code> interface) method on the created component, and uses it to supply the 'filter params'.</p>

    <pre>
agInit(params:IFilterParams):void {
    this.params = params;
    this.valueGetter = params.valueGetter;
}</pre>
    </p>

    <h3><img src="../images/angular2_large.png" style="width: 20px;"/> Angular 2 Methods / Lifecycle</h3>

    <p>
        All of the methods in the IFilter interface described above are applicable
        to the Angular 2 Component with the following exceptions:
    <ul>
        <li><i>init()</i> is not used. Instead implement the <code>agInit</code> method (on the <code>AgRendererComponent</code> interface).</li>
        <li><i>destroy()</i> is not used. Instead implement the Angular 2<code>OnDestroy</code> interface (<code>ngOnDestroy</code>) for
            any cleanup you need to do.</li>
        <li><i>getGui()</i> is not used. Angular 2 will provide the Gui via the supplied template.</li>
    </ul>

    <p>
        After that, all the other methods (<i>onNewRowsLoaded(), getModel(), setModel()</i> etc) behave the
        same so put them directly onto your Angular 2 Component.
    </p>

    <h3><img src="../images/angular2_large.png" style="width: 20px;"/> Accessing the Angular 2 Component Instance</h3>

    <p>
        ag-Grid allows you to get a reference to the filter instances via the <i>api.getFilterInstance(colKey)</i>
        method. If your component is a Angular 2 component, then this will give you a reference to the ag-Grid's
        Component which wraps your Angular 2 Component. Just like Russian Dolls. To get to the wrapped Angular 2 instance
        of your component, use the <i>getFrameworkComponentInstance()</i> method as follows:
        <pre><span class="codeComment">// lets assume a Angular 2 component as follows</span>
@Component({
    selector: 'filter-cell',
    template: `
        Filter: &lt;input style="height: 10px" #input (ngModelChange)="onChange($event)" [ngModel]="text">
    `
})
class PartialMatchFilterComponent implements AgFilterComponent {

    ... <span class="codeComment">// standard filter methods hidden</span>

    <span class="codeComment">// put a custom method on the filter</span>
    myMethod() {
        <span class="codeComment">// does something</span>
    }
}

<span class="codeComment">// then in your app, if you want to execute myMethod()...</span>
laterOnInYourApplicationSomewhere() {

    <span class="codeComment">// get reference to the ag-Grid Filter component</span>
    let agGridFilter = api.getFilterInstance('name'); <span class="codeComment">// assume filter on name column</span>

    <span class="codeComment">// get Angular 2 instance from the ag-Grid instance</span>
    let ng2FilterInstance = agGridFilter.getFrameworkComponentInstance();

    <span class="codeComment">// now were sucking diesel!!!</span>
    ng2FilterInstance.myMethod();
}</pre>
    </p>

    <h3>Example: Filtering using Angular 2 Components</h3>
    <p>
        Using Angular 2 Components as a partial text Filter in the "Filter Component" column, illustrating filtering and lifecycle events.
    </p>
    <show-example example="../ng2-example/index.html?example=filter-component" jsfile="../ng2-example/app/filter-component.component.ts" html="../ng2-example/app/filter-component.component.html"></show-example>
</div>

<?php include '../documentation-main/documentation_footer.php';?>

<?php

/**
 * Updated By PHP Office365 Generator 2020-04-22T21:18:30+00:00 16.0.20008.12009
 */
namespace Office365\SharePoint;

use Office365\Runtime\ClientValue;
/**
 * The 
 * parameters that are used to override and extend the regular 
 * SPRenderListDataParameters.
 */
class RenderListDataOverrideParameters extends ClientValue
{
    /**
     * The 
     * message that SHOULD be displayed if there is a cascade deletion warning.
     * @var string
     */
    public $CascDelWarnMessage;
    /**
     * Specifies 
     * a user 
     * custom action.
     * @var string
     */
    public $CustomAction;
    /**
     * Specifies 
     * that some groups in a grouped view are expanded. Used with GroupString 
     * (section 3.2.5.307.1.1.84).
     * @var string
     */
    public $DrillDown;
    /**
     * Specifies 
     * a special field (2) that 
     * SHOULD be included.
     * @var string
     */
    public $Field;
    /**
     * Used to 
     * identify a field when a list has an external data source. Also used when 
     * filtering on a custom field.
     * @var string
     */
    public $FieldInternalName;
    /**
     * Specifies 
     * whether the requested view has a filter applied.
     * @var string
     */
    public $Filter;
    /**
     * Data 
     * specified by a particular filter.
     * @var string
     */
    public $FilterData;
    /**
     * Data 
     * specified by a particular filter.
     * @var string
     */
    public $FilterData1;
    /**
     * Data 
     * specified by a particular filter.
     * @var string
     */
    public $FilterData10;
    /**
     * Data 
     * specified by a particular filter.
     * @var string
     */
    public $FilterData2;
    /**
     * Data 
     * specified by a particular filter.
     * @var string
     */
    public $FilterData3;
    /**
     * Data 
     * specified by a particular filter.
     * @var string
     */
    public $FilterData4;
    /**
     * Data 
     * specified by a particular filter.
     * @var string
     */
    public $FilterData5;
    /**
     * Data 
     * specified by a particular filter.
     * @var string
     */
    public $FilterData6;
    /**
     * Data 
     * specified by a particular filter.
     * @var string
     */
    public $FilterData7;
    /**
     * Data 
     * specified by a particular filter.
     * @var string
     */
    public $FilterData8;
    /**
     * Data 
     * specified by a particular filter.
     * @var string
     */
    public $FilterData9;
    /**
     * A filter 
     * field name for a specific filter that is applied to the view.
     * @var string
     */
    public $FilterField;
    /**
     * A filter 
     * field name for a specific filter that is applied to the view.
     * @var string
     */
    public $FilterField1;
    /**
     * A filter 
     * field name for a specific filter that is applied to the view.
     * @var string
     */
    public $FilterField10;
    /**
     * A filter 
     * field name for a specific filter that is applied to the view.
     * @var string
     */
    public $FilterField2;
    /**
     * A filter 
     * field name for a specific filter that is applied to the view.
     * @var string
     */
    public $FilterField3;
    /**
     * A filter 
     * field name for a specific filter that is applied to the view.
     * @var string
     */
    public $FilterField4;
    /**
     * A filter 
     * field name for a specific filter that is applied to the view.
     * @var string
     */
    public $FilterField5;
    /**
     * A filter 
     * field name for a specific filter that is applied to the view.
     * @var string
     */
    public $FilterField6;
    /**
     * A filter 
     * field name for a specific filter that is applied to the view.
     * @var string
     */
    public $FilterField7;
    /**
     * A filter 
     * field name for a specific filter that is applied to the view.
     * @var string
     */
    public $FilterField8;
    /**
     * A filter 
     * field name for a specific filter that is applied to the view.
     * @var string
     */
    public $FilterField9;
    /**
     * Specifies 
     * multiple fields that are being filtered on for a multiplier filter.
     * @var string
     */
    public $FilterFields;
    /**
     * Specifies 
     * multiple fields that are being filtered on for a multiplier filter.
     * @var string
     */
    public $FilterFields1;
    /**
     * Specifies 
     * multiple fields that are being filtered on for a multiplier filter.
     * @var string
     */
    public $FilterFields10;
    /**
     * Specifies 
     * multiple fields that are being filtered on for a multiplier filter.
     * @var string
     */
    public $FilterFields2;
    /**
     * Specifies 
     * multiple fields that are being filtered on for a multiplier filter.
     * @var string
     */
    public $FilterFields3;
    /**
     * Specifies 
     * multiple fields that are being filtered on for a multiplier filter.
     * @var string
     */
    public $FilterFields4;
    /**
     * Specifies 
     * multiple fields that are being filtered on for a multiplier filter.
     * @var string
     */
    public $FilterFields5;
    /**
     * Specifies 
     * multiple fields that are being filtered on for a multiplier filter.
     * @var string
     */
    public $FilterFields6;
    /**
     * Specifies 
     * multiple fields that are being filtered on for a multiplier filter.
     * @var string
     */
    public $FilterFields7;
    /**
     * Specifies 
     * multiple fields that are being filtered on for a multiplier filter.
     * @var string
     */
    public $FilterFields8;
    /**
     * Specifies 
     * multiple fields that are being filtered on for a multiplier filter.
     * @var string
     */
    public $FilterFields9;
    /**
     * Used when 
     * filtering on a lookup field. This is the item id in the foreign list that has a 
     * value that is being filtered on.
     * @var string
     */
    public $FilterLookupId;
    /**
     * Used when 
     * filtering on a lookup field. This is the item id in the foreign list that has a 
     * value that is being filtered on.
     * @var string
     */
    public $FilterLookupId1;
    /**
     * Used when 
     * filtering on a lookup field. This is the item id in the foreign list that has a 
     * value that is being filtered on.
     * @var string
     */
    public $FilterLookupId10;
    /**
     * Used when 
     * filtering on a lookup field. This is the item id in the foreign list that has a 
     * value that is being filtered on.
     * @var string
     */
    public $FilterLookupId2;
    /**
     * Used when 
     * filtering on a lookup field. This is the item id in the foreign list that has a 
     * value that is being filtered on.
     * @var string
     */
    public $FilterLookupId3;
    /**
     * Used when 
     * filtering on a lookup field. This is the item id in the foreign list that has a 
     * value that is being filtered on.
     * @var string
     */
    public $FilterLookupId4;
    /**
     * Used when 
     * filtering on a lookup field. This is the item id in the foreign list that has a 
     * value that is being filtered on.
     * @var string
     */
    public $FilterLookupId5;
    /**
     * Used when 
     * filtering on a lookup field. This is the item id in the foreign list that has a 
     * value that is being filtered on.
     * @var string
     */
    public $FilterLookupId6;
    /**
     * Used when 
     * filtering on a lookup field. This is the item id in the foreign list that has a 
     * value that is being filtered on.
     * @var string
     */
    public $FilterLookupId7;
    /**
     * Used when 
     * filtering on a lookup field. This is the item id in the foreign list that has a 
     * value that is being filtered on.
     * @var string
     */
    public $FilterLookupId8;
    /**
     * Used when 
     * filtering on a lookup field. This is the item id in the foreign list that has a 
     * value that is being filtered on.
     * @var string
     */
    public $FilterLookupId9;
    /**
     * Filter 
     * operator. Used when filtering with other operators than Eq (Geq, Leq etc.).
     * @var string
     */
    public $FilterOp;
    /**
     * Filter 
     * operator. Used when filtering with other operators than Eq (Geq, Leq etc.).
     * @var string
     */
    public $FilterOp1;
    /**
     * Filter 
     * operator. Used when filtering with other operators than Eq (Geq, Leq etc.).
     * @var string
     */
    public $FilterOp10;
    /**
     * Filter 
     * operator. Used when filtering with other operators than Eq (Geq, Leq etc.).
     * @var string
     */
    public $FilterOp2;
    /**
     * Filter 
     * operator. Used when filtering with other operators than Eq (Geq, Leq etc.).
     * @var string
     */
    public $FilterOp3;
    /**
     * Filter 
     * operator. Used when filtering with other operators than Eq (Geq, Leq etc.).
     * @var string
     */
    public $FilterOp4;
    /**
     * Filter 
     * operator. Used when filtering with other operators than Eq (Geq, Leq etc.).
     * @var string
     */
    public $FilterOp5;
    /**
     * Filter 
     * operator. Used when filtering with other operators than Eq (Geq, Leq etc.).
     * @var string
     */
    public $FilterOp6;
    /**
     * Filter 
     * operator. Used when filtering with other operators than Eq (Geq, Leq etc.).
     * @var string
     */
    public $FilterOp7;
    /**
     * Filter 
     * operator. Used when filtering with other operators than Eq (Geq, Leq etc.).
     * @var string
     */
    public $FilterOp8;
    /**
     * Filter 
     * operator. Used when filtering with other operators than Eq (Geq, Leq etc.).
     * @var string
     */
    public $FilterOp9;
    /**
     * The filter 
     * value associated with a particular filter. For example, FilterField3 goes with 
     * FilterValue3 and so forth.
     * @var string
     */
    public $FilterValue;
    /**
     * The filter 
     * value associated with a particular filter. For example, FilterField3 goes with 
     * FilterValue3 and so forth.
     * @var string
     */
    public $FilterValue1;
    /**
     * The filter 
     * value associated with a particular filter. For example, FilterField3 goes with 
     * FilterValue3 and so forth.
     * @var string
     */
    public $FilterValue10;
    /**
     * The filter 
     * value associated with a particular filter. For example, FilterField3 goes with 
     * FilterValue3 and so forth.
     * @var string
     */
    public $FilterValue2;
    /**
     * The filter 
     * value associated with a particular filter. For example, FilterField3 goes with FilterValue3 
     * and so forth.
     * @var string
     */
    public $FilterValue3;
    /**
     * The filter 
     * value associated with a particular filter. For example, FilterField3 goes with 
     * FilterValue3 and so forth.
     * @var string
     */
    public $FilterValue4;
    /**
     * The filter 
     * value associated with a particular filter. For example, FilterField3 goes with 
     * FilterValue3 and so forth.
     * @var string
     */
    public $FilterValue5;
    /**
     * The filter 
     * value associated with a particular filter. For example, FilterField3 goes with 
     * FilterValue3 and so forth.
     * @var string
     */
    public $FilterValue6;
    /**
     * The filter 
     * value associated with a particular filter. For example, FilterField3 goes with 
     * FilterValue3 and so forth.
     * @var string
     */
    public $FilterValue7;
    /**
     * The filter 
     * value associated with a particular filter. For example, FilterField3 goes with 
     * FilterValue3 and so forth.
     * @var string
     */
    public $FilterValue8;
    /**
     * The filter 
     * value associated with a particular filter. For example, FilterField3 goes with 
     * FilterValue3 and so forth.
     * @var string
     */
    public $FilterValue9;
    /**
     * Used with FilterFields 
     * (section 3.2.5.307.1.1.29) 
     * for multiplier filter.
     * @var string
     */
    public $FilterValues;
    /**
     * Used with FilterFields1 
     * (section 3.2.5.307.1.1.30) 
     * for multiplier filter.
     * @var string
     */
    public $FilterValues1;
    /**
     * Used with FilterFields10 
     * (section 3.2.5.307.1.1.31) 
     * for multiplier filter.
     * @var string
     */
    public $FilterValues10;
    /**
     * Used with FilterFields2 
     * (section 3.2.5.307.1.1.32) 
     * for multiplier filter.
     * @var string
     */
    public $FilterValues2;
    /**
     * Used with FilterFields3 
     * (section 3.2.5.307.1.1.33) 
     * for multiplier filter.
     * @var string
     */
    public $FilterValues3;
    /**
     * Used with FilterFields4 
     * (section 3.2.5.307.1.1.34) 
     * for multiplier filter.
     * @var string
     */
    public $FilterValues4;
    /**
     * Used with FilterFields5 
     * (section 3.2.5.307.1.1.35) 
     * for multiplier filter.
     * @var string
     */
    public $FilterValues5;
    /**
     * Used with FilterFields6 
     * (section 3.2.5.307.1.1.36) 
     * for multiplier filter.
     * @var string
     */
    public $FilterValues6;
    /**
     * Used with FilterFields7 
     * (section 3.2.5.307.1.1.37) 
     * for multiplier filter.
     * @var string
     */
    public $FilterValues7;
    /**
     * Used with FilterFields8 
     * (section 3.2.5.307.1.1.38) 
     * for multiplier filter.
     * @var string
     */
    public $FilterValues8;
    /**
     * Used with FilterFields9 
     * (section 3.2.5.307.1.1.39) 
     * for multiplier filter.
     * @var string
     */
    public $FilterValues9;
    /**
     * Group 
     * identifier used for drill-down feature.
     * @var string
     */
    public $GroupString;
    /**
     * Used to 
     * ensure that certain fields are present for proper functioning of the ListView 
     * control.
     * @var string
     */
    public $HasOverrideSelectCommand;
    /**
     * The item 
     * id of the item whose information is being sought.
     * @var string
     */
    public $ID;
    /**
     * Specifies 
     * whether there is a full list search. "true" if there is a full list 
     * search; "false" otherwise.
     * @var string
     */
    public $InplaceFullListSearch;
    /**
     * Search 
     * term for a full list search.
     * @var string
     */
    public $InplaceSearchQuery;
    /**
     * Specifies 
     * whether this view is a client side rendered view.
     * @var string
     */
    public $IsCSR;
    /**
     * Used to 
     * set the IsGroupRender property of the SPView.
     * @var string
     */
    public $IsGroupRender;
    /**
     * Specifies 
     * whether this view is an xslt list view.
     * @var string
     */
    public $IsXslView;
    /**
     * The URL of 
     * the view that is being displayed.
     * @var string
     */
    public $ListViewPageUrl;
    /**
     * Used to 
     * override a scope on the rendered view:  SPView.Scope.
     * @var string
     */
    public $OverrideScope;
    /**
     * Used to 
     * ensure that certain fields are present for proper functioning of the ListView 
     * control.
     * @var string
     */
    public $OverrideSelectCommand;
    /**
     * Paging 
     * information about the first row that is requested. Used for paging list views.
     * @var string
     */
    public $PageFirstRow;
    /**
     * Paging information 
     * about the last row that is requested. Used for paging list views.
     * @var string
     */
    public $PageLastRow;
    /**
     * The folder 
     * that the view is displaying.
     * @var string
     */
    public $RootFolder;
    /**
     * @var string
     */
    public $RootFolderUniqueId;
    /**
     * The sort 
     * direction of an ad hoc sort that is being applied to the view.
     * @var string
     */
    public $SortDir;
    /**
     * The sort 
     * direction of an ad hoc sort that is being applied to the view.
     * @var string
     */
    public $SortDir1;
    /**
     * The sort 
     * direction of an ad hoc sort that is being applied to the view.
     * @var string
     */
    public $SortDir10;
    /**
     * The sort 
     * direction of an ad hoc sort that is being applied to the view.
     * @var string
     */
    public $SortDir2;
    /**
     * The sort 
     * direction of an ad hoc sort that is being applied to the view.
     * @var string
     */
    public $SortDir3;
    /**
     * The sort 
     * direction of an ad hoc sort that is being applied to the view.
     * @var string
     */
    public $SortDir4;
    /**
     * The sort 
     * direction of an ad hoc sort that is being applied to the view.
     * @var string
     */
    public $SortDir5;
    /**
     * The sort 
     * direction of an ad hoc sort that is being applied to the view.
     * @var string
     */
    public $SortDir6;
    /**
     * The sort 
     * direction of an ad hoc sort that is being applied to the view.
     * @var string
     */
    public $SortDir7;
    /**
     * The sort direction 
     * of an ad hoc sort that is being applied to the view.
     * @var string
     */
    public $SortDir8;
    /**
     * The sort 
     * direction of an ad hoc sort that is being applied to the view.
     * @var string
     */
    public $SortDir9;
    /**
     * A field 
     * that the view SHOULD be sorted on.
     * @var string
     */
    public $SortField;
    /**
     * A field 
     * that the view SHOULD be sorted on.
     * @var string
     */
    public $SortField1;
    /**
     * A field 
     * that the view SHOULD be sorted on.
     * @var string
     */
    public $SortField10;
    /**
     * A field 
     * that the view SHOULD be sorted on.
     * @var string
     */
    public $SortField2;
    /**
     * A field 
     * that the view SHOULD be sorted on.
     * @var string
     */
    public $SortField3;
    /**
     * A field 
     * that the view SHOULD be sorted on.
     * @var string
     */
    public $SortField4;
    /**
     * A field 
     * that the view SHOULD be sorted on.
     * @var string
     */
    public $SortField5;
    /**
     * A field 
     * that the view SHOULD be sorted on.
     * @var string
     */
    public $SortField6;
    /**
     * A field 
     * that the view SHOULD be sorted on.
     * @var string
     */
    public $SortField7;
    /**
     * A field 
     * that the view SHOULD be sorted on.
     * @var string
     */
    public $SortField8;
    /**
     * A field 
     * that the view SHOULD be sorted on.
     * @var string
     */
    public $SortField9;
    /**
     * Specifies 
     * the name of the first Field to sort by.
     * @var string
     */
    public $SortFields;
    /**
     * Specifies 
     * the name of the first Field to sort by.
     * @var string
     */
    public $SortFieldValues;
    /**
     * Specifies 
     * whether this view is an xslt list view.
     * @var string
     */
    public $View;
    /**
     * When 
     * multiple list views are on a page, this identifies one of them.
     * @var string
     */
    public $ViewCount;
    /**
     * Specifies 
     * the base view that will be used to render the list. Ad hoc parameters will be 
     * applied on top of this view. If both ViewXml and BaseViewId are given, then the 
     * ViewXml will be used and the ad hoc parameters will be ignored.
     * @var string
     */
    public $ViewId;
    /**
     * Specify 
     * the path of the view that will be used to render the list. If ViewId is given then 
     * the ViewId will be used and this parameters will be ignored.
     * @var string
     */
    public $ViewPath;
    /**
     * The id of 
     * the list view web part that is showing this view.
     * @var string
     */
    public $WebPartId;
    /**
     * @var KeyValueCollection
     */
    public $QueryParams;
}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Rosary Prayers API</title>
<style type="text/css">
body {
	font-size:13px;
}
</style>

</head>

<body>

<strong style="text-decoration:underline;color:#F00; font-size:30px;">
    Rosary Prayers API
</strong>
<br><br>

<div class="current tab-pane fade" id="tab-horizontal-30" role="tabpanel" aria-labelledby="tab-horizontal-30-tab">

    {{-- 1. Rosary Category List --}}
    <fieldset style="border:2px solid; border-color:#162133">
        <form name="form" method="POST" action="{{ url('/api/categories') }}">
            <table border="0" width="100%">
                <tr>
                    <td height="30" colspan="2" align="left">
                        <strong style="text-decoration:underline;color:#F00;"> 1 . Rosary Category List [ Development Mode ] </strong><br>
                        <div><strong>API URL:</strong> {{ url('/api/categories') }}</div>
                        <div><strong>Method:</strong> POST </div>
                        <div><strong>Content-Type:</strong> application/x-www-form-urlencoded [i.e. x-www-form-urlencoded]</div>
                        <div><strong>API Mode:</strong> Development </div>
                        <div><strong>Notes:</strong> General Prayers screen. The "next" field tells which API to call on tap: subCategories = Sub Category List API, prayers = Prayer List API </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <table width="100%" border="0" cellspacing="2" cellpadding="2">
                            <tr style="background-color:#162133; color:#FFF;">
                                <td><strong>Label Name</strong></td>
                                <td><strong>Value</strong></td>
                                <td><strong>Variable Name</strong></td>
                                <td><strong>Mandatory</strong></td>
                                <td><strong>Note</strong></td>
                                <td><strong>Sample</strong></td>
                            </tr>
                            <tr>
                                <td colspan="6">No parameters required</td>
                            </tr>
                            <tr>
                                <td colspan="1" align="center"><input type="submit" value="Submit" /></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </form>
    </fieldset>
    <br><br>

    {{-- 2. Rosary Sub Category List --}}
    <fieldset style="border:2px solid; border-color:#162133">
        <form name="form" method="POST" action="{{ url('/api/sub-categories') }}">
            <table border="0" width="100%">
                <tr>
                    <td height="30" colspan="2" align="left">
                        <strong style="text-decoration:underline;color:#F00;"> 2 . Rosary Sub Category List [ Development Mode ] </strong><br>
                        <div><strong>API URL:</strong> {{ url('/api/sub-categories') }}</div>
                        <div><strong>Method:</strong> POST </div>
                        <div><strong>Content-Type:</strong> application/x-www-form-urlencoded [i.e. x-www-form-urlencoded]</div>
                        <div><strong>API Mode:</strong> Development </div>
                        <div><strong>Notes:</strong> Use when "next" is subCategories. Daily Prayer screen: Morning, Evening, Night, Before Meals </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <table width="100%" border="0" cellspacing="2" cellpadding="2">
                            <tr style="background-color:#162133; color:#FFF;">
                                <td><strong>Label Name</strong></td>
                                <td><strong>Value</strong></td>
                                <td><strong>Variable Name</strong></td>
                                <td><strong>Mandatory</strong></td>
                                <td><strong>Note</strong></td>
                                <td><strong>Sample</strong></td>
                            </tr>
                            <tr>
                                <td>Category ID</td>
                                <td><input type="text" name="id" /></td>
                                <td>id</td>
                                <td>Yes</td>
                                <td>From Rosary Category List API</td>
                                <td>1 (string)</td>
                            </tr>
                            <tr>
                                <td>Page</td>
                                <td><input type="text" name="page" /></td>
                                <td>page</td>
                                <td>No</td>
                                <td>Default 1</td>
                                <td>1 (string)</td>
                            </tr>
                            <tr>
                                <td colspan="1" align="center"><input type="submit" value="Submit" /></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </form>
    </fieldset>
    <br><br>

    {{-- 3. Rosary Prayer List --}}
    <fieldset style="border:2px solid; border-color:#162133">
        <form name="form" method="POST" action="{{ url('/api/categories/prayers') }}">
            <table border="0" width="100%">
                <tr>
                    <td height="30" colspan="2" align="left">
                        <strong style="text-decoration:underline;color:#F00;"> 3 . Rosary Prayer List [ Development Mode ] </strong><br>
                        <div><strong>API URL:</strong> {{ url('/api/categories/prayers') }}</div>
                        <div><strong>Method:</strong> POST </div>
                        <div><strong>Content-Type:</strong> application/x-www-form-urlencoded [i.e. x-www-form-urlencoded]</div>
                        <div><strong>API Mode:</strong> Development </div>
                        <div><strong>Notes:</strong> Send sub_category_id for the prayers of a sub category (Prayer title 1, 2 ...). Or send id for a category that has no sub categories. Send only one of them. </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <table width="100%" border="0" cellspacing="2" cellpadding="2">
                            <tr style="background-color:#162133; color:#FFF;">
                                <td><strong>Label Name</strong></td>
                                <td><strong>Value</strong></td>
                                <td><strong>Variable Name</strong></td>
                                <td><strong>Mandatory</strong></td>
                                <td><strong>Note</strong></td>
                                <td><strong>Sample</strong></td>
                            </tr>
                            <tr>
                                <td>Sub Category ID</td>
                                <td><input type="text" name="sub_category_id" /></td>
                                <td>sub_category_id</td>
                                <td>Yes (if id is empty)</td>
                                <td>From Rosary Sub Category List API</td>
                                <td>1 (string)</td>
                            </tr>
                            <tr>
                                <td>Category ID</td>
                                <td><input type="text" name="id" /></td>
                                <td>id</td>
                                <td>Yes (if sub_category_id is empty)</td>
                                <td>From Rosary Category List API</td>
                                <td>3 (string)</td>
                            </tr>
                            <tr>
                                <td>Page</td>
                                <td><input type="text" name="page" /></td>
                                <td>page</td>
                                <td>No</td>
                                <td>Default 1</td>
                                <td>1 (string)</td>
                            </tr>
                            <tr>
                                <td colspan="1" align="center"><input type="submit" value="Submit" /></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </form>
    </fieldset>
    <br><br>

    {{-- 4. Rosary Prayer Detail --}}
    {{-- <fieldset style="border:2px solid; border-color:#162133">
        <form name="form" method="POST" action="{{ url('/api/prayers/detail') }}">
            <table border="0" width="100%">
                <tr>
                    <td height="30" colspan="2" align="left">
                        <strong style="text-decoration:underline;color:#F00;"> 4 . Rosary Prayer Detail [ Development Mode ] </strong><br>
                        <div><strong>API URL:</strong> {{ url('/api/prayers/detail') }}</div>
                        <div><strong>Method:</strong> POST </div>
                        <div><strong>Content-Type:</strong> application/x-www-form-urlencoded [i.e. x-www-form-urlencoded]</div>
                        <div><strong>API Mode:</strong> Development </div>
                        <div><strong>Notes:</strong> Prayer detail screen: heading and paragraphs </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <table width="100%" border="0" cellspacing="2" cellpadding="2">
                            <tr style="background-color:#162133; color:#FFF;">
                                <td><strong>Label Name</strong></td>
                                <td><strong>Value</strong></td>
                                <td><strong>Variable Name</strong></td>
                                <td><strong>Mandatory</strong></td>
                                <td><strong>Note</strong></td>
                                <td><strong>Sample</strong></td>
                            </tr>
                            <tr>
                                <td>Prayer ID</td>
                                <td><input type="text" name="id" /></td>
                                <td>id</td>
                                <td>Yes</td>
                                <td>From Rosary Prayer List API</td>
                                <td>11 (string)</td>
                            </tr>
                            <tr>
                                <td colspan="1" align="center"><input type="submit" value="Submit" /></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </form>
    </fieldset> --}}

</div>

<br><br>
</body>

</html>
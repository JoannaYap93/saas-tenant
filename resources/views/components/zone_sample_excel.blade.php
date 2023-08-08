<div class="table-responsive">
    @if($zone_trees && count($zone_trees) > 0)
      <table class="table table-bordered">
        <thead>
            <tr>
                <th style="min-width: 220px; border:1px solid #eee" class="table-secondary">ID</th>
                <th class="table-secondary">Code</th>
                <th class="table-secondary">Tree No.</th>
                <th class="table-secondary">Product</th>
                <th class="table-secondary">Inch</th>
                <th class="table-secondary">Sick</th>
                <th class="table-secondary">Bear Fruits</th>
                <th class="table-secondary">Status</th>
                <th class="table-secondary">Tree Year</th>
            </tr>
        </thead>
        <tbody>
          @foreach($zone_trees as $key => $zone_tree)
            <tr>
                <td>{{$zone_tree->company_land_tree_id}}</td>
                <td>{{$zone_tree->company_pnl_sub_item_code}}</td>
                <td>{{$zone_tree->company_land_tree_no}}</td>
                <td>{{@$zone_tree->product->product_name}}</td>
                <td>{{$zone_tree->company_land_tree_circumference}}</td>
                <td>{{$zone_tree->is_sick == 1 ? "yes" : "no"}}</td>
                <td>{{$zone_tree->is_bear_fruit == 1 ? "yes" : "no"}}</td>
                <td>{{$zone_tree->company_land_tree_status}}</td>
                <td>{{$zone_tree->company_land_tree_year}}</td>
            </tr>
          @endforeach
          <tr></tr>
          <tr></tr>
          <tr></tr>
          <tr></tr>
          <tr>
              <td colspan="{{count($product_company_land) + 1}}" align="center" class="table-secondary">Sample Input</td>
          </tr>
          <tr>
            <td><b>Products</b></td>
            @foreach($product_company_land as $key => $pcl)
              <td>{{$pcl->product->product_name}}</td>
            @endforeach
          </tr>
          <tr>
            <td><b>Sick</b></td>
            <td>yes</td>
            <td>no</td>
          </tr>
          <tr>
            <td><b>Bear Fruits</b></td>
            <td>yes</td>
            <td>no</td>
          </tr>
          <tr>
            <td><b>Status</b></td>
            <td>alive</td>
            <td>dead</td>
          </tr>
          <tr>
            <td><b>Code</b></td>
            <td>K1(New Tree)</td>
            <td>B10(Below 10 Years)</td>
            <td>A10(Above 10 Years)</td>
            <td>KM(Old Tree Grafted Musang)</td>
            <td>KB(Old Tree Grafted Black Thorn)</td>
          </tr>
        </tbody>
      </table>
    @else
      <table class="table table-bordered">
        <thead>
            <tr>
                <th style="min-width: 220px; border:1px solid #eee" class="table-secondary">Code</th>
                <th class="table-secondary">Tree No.</th>
                <th class="table-secondary">Product</th>
                <th class="table-secondary">Inch</th>
                <th class="table-secondary">Cm</th>
                <th class="table-secondary">Sick</th>
                <th class="table-secondary">Bear Fruits</th>
                <th class="table-secondary">Status</th>
                <th class="table-secondary">Tree Year</th>
            </tr>

        </thead>
        <tbody>
            <tr>
                <td>K1(sample)</td>
                <td>24(Sample)</td>
                <td>101(Sample)</td>
                <td>25(Sample)</td>
                <td>0(Sample)</td>
                <td>no(Sample)</td>
                <td>yes(Sample)</td>
                <td>alive(Sample)</td>
                <td>2023(Sample)</td>
            </tr>
            <tr></tr>
            <tr></tr>
            <tr></tr>
            <tr></tr>
            <tr>
                <td colspan="{{count($product_company_land) + 1}}" align="center" class="table-secondary">Sample Input</td>
            </tr>
            <tr>
              <td><b>Products</b></td>
              @foreach($product_company_land as $key => $pcl)
                <td>{{$pcl->product->product_name}}</td>
              @endforeach
            </tr>
            <tr>
              <td><b>Sick</b></td>
              <td>yes</td>
              <td>no</td>
            </tr>
            <tr>
              <td><b>Bear Fruits</b></td>
              <td>yes</td>
              <td>no</td>
            </tr>
            <tr>
              <td><b>Status</b></td>
              <td>alive</td>
              <td>dead</td>
            </tr>
            <tr>
                <td><b>Code</b></td>
                <td>K1(New Tree)</td>
                <td>B10(Below 10 Years)</td>
                <td>A10(Above 10 Years)</td>
                <td>KM(Old Tree Grafted Musang)</td>
                <td>KB(Old Tree Grafted Black Thorn)</td>
            </tr>
        </tbody>
      </table>
    @endif
</div>

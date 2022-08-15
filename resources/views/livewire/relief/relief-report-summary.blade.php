<div>
    
    <div class="card-header text-uppercase font-weight-bold bg-grey ">
        Relieved Amounts
    </div>
    <table class="table table-bordered table-striped table-sm">
        <thead>
            <tr class="text-center">
                <th scope="col"></th>
                @foreach ($projectSectionsArray as $project)
                    <th scope="col"> {{ $project['name'] }}</th>
                @endforeach
                <th scope="col" class="bg-secondary">TOTAL</th>
            </tr>

        </thead>
        @php
            $totalAllMonths = 0;
        @endphp
        @if (!$data)
            <tr class="text-center">
                <td colspan="{{ count($projectSectionsArray) + 2 }}">
                    No Data
                </td>
            </tr>
        @else
            @foreach ($data as $month => $content)
                <tr class="text-center">
                    <td>
                        {{ $month }}
                    </td>
                    @php
                        $sumMonth[$month] = 0;
                    @endphp
                    @foreach ($content as $key => $projectSection)
                        @php
                            $sumAmountProjectSection[$key] = $sumAmountProjectSection[$key] ?? 0;
                        @endphp
                        <td>
                            {{ $projectSection['relievedAmount'] == 0 ? '-' : number_format($projectSection['relievedAmount'], 1) }}
                        </td>
                        <!--append the sum -->
                        @php
                            $sumMonth[$month] += $projectSection['relievedAmount'];
                            $sumAmountProjectSection[$key] += $projectSection['relievedAmount'];
                            $totalAllMonths = $totalAllMonths + $projectSection['relievedAmount'];
                        @endphp
                    @endforeach
                    <td class="bg-secondary font-weight-bold">
                        {{ $sumMonth[$month] == 0 ? '-' : number_format($sumMonth[$month], 1) }}
                    </td>
                </tr>
            @endforeach
            <tr class="text-center">
                <td class="bg-secondary font-weight-bold">
                    TOTAL
                </td>
                @foreach ($sumAmountProjectSection as $key => $value)
                    <td class="bg-secondary font-weight-bold">
                        {{ $value == 0 ? '-' : number_format($value, 1) }}
                    </td>
                @endforeach
                <td class="bg-secondary font-weight-bold">
                    {{ $totalAllMonths == 0 ? '-' : number_format($totalAllMonths, 1) }}
                </td>
            </tr>
        @endif


    </table>
    <div class="pt-3"></div>
    <div class="card-header text-uppercase font-weight-bold bg-grey ">
        Relief Applications Count
    </div>
    <table class="table table-bordered table-striped table-sm">
        <thead>
            <tr class="text-center">
                <th scope="col"></th>
                @foreach ($projectSectionsArray as $project)
                    <th scope="col"> {{ $project['name'] }}</th>
                @endforeach
                <th scope="col" class="bg-secondary">TOTAL</th>
            </tr>

        </thead>
        @php
            $totalAllMonths = 0;
        @endphp
        @if (!$data)
            <tr class="text-center">
                <td colspan="{{ count($projectSectionsArray) + 2 }}">
                    No Data
                </td>
            </tr>
        @else
            @foreach ($data as $month => $content)
                <tr class="text-center">
                    <td>
                        {{ $month }}
                    </td>
                    @php
                        $sumMonth[$month] = 0;
                    @endphp
                    @foreach ($content as $key => $projectSection)
                        @php
                            $sumProjectSection[$key] = $sumProjectSection[$key] ?? 0;
                        @endphp
                        <td>
                            {{ $projectSection['count'] == 0 ? '-' : $projectSection['count'] }}
                        </td>
                        <!--append the sum -->
                        @php
                            $sumMonth[$month] += $projectSection['count'];
                            $sumProjectSection[$key] += $projectSection['count'];
                            $totalAllMonths = $totalAllMonths + $projectSection['count'];
                        @endphp
                    @endforeach
                    <td class="bg-secondary font-weight-bold">
                        {{ $sumMonth[$month] == 0 ? '-' : $sumMonth[$month] }}
                    </td>
                </tr>
            @endforeach
            <tr class="text-center">
                <td class="bg-secondary font-weight-bold">
                    TOTAL
                </td>
                @foreach ($sumProjectSection as $key => $value)
                    <td class="bg-secondary font-weight-bold">
                        {{ $value == 0 ? '-' : $value }}
                    </td>
                @endforeach
                <td class="bg-secondary font-weight-bold">
                    {{ $totalAllMonths == 0 ? '-' : $totalAllMonths }}
                </td>
            </tr>
        @endif
    </table>
    
</div>

import "@typespec/http";
import "@typespec/rest";
import "@typespec/openapi3";

import "./models/index.tsp";
import "./responses/index.tsp";
import "./payloads/index.tsp";

using TypeSpec.Http;
using TypeSpec.Rest;

@service(#{
  title: "Test",
})
@server("/api", "test")
//@useAuth(BearerAuth)
namespace Application;

@tag("Test")
@route("/tests")
interface TestOps {
  @get index(): IndexResponse<Test>;
  @post create(@body TestBody: Test): CreatedResponse<Test>;
  @route("{id}")
  @get get(@path id: int32): RecordResponse<Test>;
  @patch update(@path id: int32, @body TestBody: Test): UpdatedResponse<Test>;
  @delete delete(@path id: int32): DeletedResponse;
}

@tag("Whatevers")
@useAuth(BearerAuth)
@route("/whatevers")
interface WhateverOps {
  @get list(...PaginatedRequest): PaginatedResponse<Whatever>;
  @post post(@body WhateverBody: Whatever): CreatedResponse<Whatever>;
  @route("{id}}")
  @get get(@path id: string): RecordResponse<Whatever>;
  @patch update(
    @path id: string,
    @body WhateverBody: Whatever,
  ): UpdatedResponse<Whatever>;
  @delete delete(@path id: string): DeletedResponse;
}

@tag("Players")
@useAuth(BearerAuth)
@route("/players")
interface PlayerOps {
  @get list(...PaginatedRequest): PaginatedResponse<Player>;
  @post post(@body PlayerBody: Player): CreatedResponse<Player>;
  @route("{id}}")
  @get get(@path id: string): RecordResponse<Player>;
  @patch update(
    @path id: string,
    @body PlayerBody: Player,
  ): UpdatedResponse<Player>;
  @delete delete(@path id: string): DeletedResponse;
}

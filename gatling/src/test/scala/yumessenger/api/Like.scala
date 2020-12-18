package yumessenger.api

import io.gatling.core.Predef._
import io.gatling.core.structure.ChainBuilder
import io.gatling.http.Predef._

object Like {
  val create: ChainBuilder =
    exec(
      http("like.create")
        .post("/likes")
        .header(HttpHeaderNames.Authorization, "Bearer ${token}")
        .body(
          StringBody(
            """
              {
                "message_id": "${message_id}"
              }
            """
              .stripMargin
          )
        )
        .check(status.is(201))
        .check(jsonPath("$.user.id").is("${user_id}"))
        .check(jsonPath("$.createdAt").exists)
        .check(jsonPath("$.id").saveAs("like_id"))
    )

  val delete: ChainBuilder =
    exec(
      http("like.delete")
        .delete("/likes/${like_id}")
        .header(HttpHeaderNames.Authorization, "Bearer ${token}")
        .check(status.is(204))
    )
}
